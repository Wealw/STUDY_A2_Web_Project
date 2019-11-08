/*
 * API_User
 * Une API REST pour permettre la connection d'utilisateur aux sites des BDE de CESI.
 * Par Théo WEIMANN
 * 08 / 11 / 19
 * Version 0.1
 */
const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const app = express();


// Connection to the database
const database = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'bde_users_api'
});
database.connect();

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({
    extended: true
}));

// Default route
app.get('/', function (req, res) {
    return res.send({error: true, message: 'default'})
});


// /Users routes
// List all users
app.get('/users', function (req, res) {
    try {
        database.query('SELECT * FROM users;', function (error, results, fields) {
            if (error) return res.status(404).send();
            res.status(200).set('Content-type', 'application/json').send(results);
        });
    } catch (e) {
        return res.status(500).send();
    }
});

// Remove the specified user
app.delete('/users', function (req, res) {
    try {
        database.query("DELETE FROM users;", function (error, results, fields) {
            if (error) return res.status(404).send();
            return res.status(202).send();
        });
    } catch (e) {
        return res.status(500).send();
    }
});

// Rewrite all attributes of the specified user
app.post('/users', function (req, res) {
    try {
        let user_first_name = req.body.user_first_name;
        let user_last_name = req.body.user_last_name;
        let user_mail = req.body.user_mail;
        let user_phone = req.body.user_phone;
        let user_postal_code = req.body.user_postal_code;
        let user_address = req.body.user_address;
        let user_city = req.body.user_city;
        let user_password = req.body.user_password;
        let user_image_path = req.body.user_image_path;
        let center_id = req.body.center_id;
        let role_id = req.body.role_id;
        if (!(user_first_name && user_last_name && user_mail && user_phone && user_postal_code && user_address && user_city && user_password && user_image_path && center_id && role_id)) {
            return res.status(422).send();
        }

        database.query("INSERT INTO bde_users_api.users (user_first_name, user_last_name, user_mail, user_phone, user_postal_code, user_address, user_city, user_password, user_image_path, created_at, modified_at, center_id, role_id) VALUES (?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,?);", [user_first_name, user_last_name, user_mail, user_phone, user_postal_code, user_address, user_city, user_password, user_image_path, center_id, role_id], function (error, results, fields) {
            if (error) return res.status(404).send();
            return res.status(202).send();
        });
    } catch (e) {
        return res.status(500).send();
    }
});

// Modify multiple user
app.put('/users', function (req, res) {
    try {
        //let base_request = 'UPDATE bde_users_api.users SET user_first_name= ? ,user_last_name= ? ,user_mail= ? ,user_phone= ? ,user_postal_code= ? ,user_address= ? ,user_city= ? ,user_password= ? ,user_image_path= ? ,modified_at= CURRENT_TIMESTAMP ,center_id= ? ,role_id= ? WHERE user_id = ? ;';
        database.query('START TRANSACTION;', [], function (error, results, fields) {
            if (error) return res.status(500).send();
        });
        req.body.forEach(function (entry) {
                let user_id = entry.user_id;
                if (!user_id) return res.status().send();
                let user_first_name = entry.user_first_name;
                let user_last_name = entry.user_last_name;
                let user_mail = entry.user_mail;
                let user_phone = entry.user_phone;
                let user_postal_code = entry.user_postal_code;
                let user_address = entry.user_address;
                let user_city = entry.user_city;
                let user_password = entry.user_password;
                let user_image_path = entry.user_image_path;
                let center_id = entry.center_id;
                let role_id = entry.role_id;

                if (!(user_first_name && user_last_name && user_mail && user_phone && user_postal_code && user_address && user_city && user_password && user_image_path && center_id && role_id)) {
                    return res.status(522).send();
                }
                database.query("SELECT * FROM users WHERE user_id= ? ;", [user_id], function (error, results, fields) {
                    if (error) return res.status(410).send();
                    if (results[0] === undefined) return res.status(410).send();
                });
                database.query("UPDATE bde_users_api.users SET user_first_name = ? , user_last_name = ?, user_mail = ? ,user_phone = ? , user_postal_code = ?, user_address = ?, user_city = ? , user_password = ? , user_image_path = ? , modified_at = CURRENT_TIMESTAMP , center_id = ? , role_id = ? WHERE user_id = ?", [user_first_name, user_last_name, user_mail, user_phone, user_postal_code, user_address, user_city, user_password, user_image_path, center_id, role_id, user_id], function (error, results, fields) {
                    if (error) return res.status(500).send();
                    return res.status(202).send();
                });
            }
        );

        database.query('COMMIT;', [], function (error, results, fields) {
            if (error) return res.status(500).send();
            return res.status(202).send();
        })
    } catch
        (e) {
        //console.log(e);
        return res.status(500).send();
    }
})
;

// Patch multiple user
app.patch('/users', function (req, res) {
    try {
        //let base_request = 'UPDATE bde_users_api.users SET user_first_name= ? ,user_last_name= ? ,user_mail= ? ,user_phone= ? ,user_postal_code= ? ,user_address= ? ,user_city= ? ,user_password= ? ,user_image_path= ? ,modified_at= CURRENT_TIMESTAMP ,center_id= ? ,role_id= ? WHERE user_id = ? ;';
        database.query('START TRANSACTION;', [], function (error, results, fields) {
            if (error) return res.status(500).send();
        });
        req.body.forEach(function (entry) {
                let user_id = entry.user_id;
                if (!user_id) return res.status().send();
                database.query("SELECT * FROM users WHERE user_id= ? ;", [user_id], function (error, results, fields) {
                    if (error) return res.status(404).send();
                    if (results[0] === undefined) return res.status(410).send();
                });
                let user_first_name = entry.user_first_name;
                if (user_first_name) {
                    database.query('UPDATE bde_users_api.users SET user_first_name= ? WHERE user_id = ? ;', [user_first_name, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let user_last_name = entry.user_last_name;
                if (user_last_name) {
                    database.query('UPDATE bde_users_api.users SET user_last_name= ? WHERE user_id = ? ;', [user_last_name, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let user_mail = entry.user_mail;
                if (user_mail) {
                    database.query('UPDATE bde_users_api.users SET user_mail= ? WHERE user_id = ? ;', [user_mail, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let user_phone = entry.user_phone;
                if (user_phone) {
                    database.query('UPDATE bde_users_api.users SET user_phone= ? WHERE user_id = ? ;', [user_phone, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let user_postal_code = entry.user_postal_code;
                if (user_postal_code) {
                    database.query('UPDATE bde_users_api.users SET user_postal_code= ? WHERE user_id = ? ;', [user_postal_code, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let user_address = entry.user_address;
                if (user_address) {
                    database.query('UPDATE bde_users_api.users SET user_address= ? WHERE user_id = ? ;', [user_address, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let user_city = entry.user_city;
                if (user_city) {
                    database.query('UPDATE bde_users_api.users SET user_city= ? WHERE user_id = ? ;', [user_city, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let user_password = entry.user_password;
                if (user_password) {
                    database.query('UPDATE bde_users_api.users SET  user_password= ? WHERE user_id = ? ;', [user_password, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let user_image_path = entry.user_image_path
                if (user_image_path) {
                    database.query('UPDATE bde_users_api.users SET user_image_path = ? WHERE user_id = ? ;', [user_image_path, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let center_id = entry.center_id;
                if (center_id) {
                    database.query('UPDATE bde_users_api.users SET center_id= ? WHERE user_id = ? ;', [center_id, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                let role_id = entry.role_id;
                if (role_id) {
                    database.query('UPDATE bde_users_api.users SET role_id= ? WHERE user_id = ? ;', [role_id, user_id], function (error, results, fields) {
                        if (error) return res.status(422).send();
                    });
                }
                /*
                if (!(user_first_name && user_last_name && user_mail && user_phone && user_postal_code && user_address && user_city && user_password && user_image_path && center_id && role_id)) {
                    return res.status(503).send();
                }
                */
            }
        );

        database.query('COMMIT;', [], function (error, results, fields) {
            if (error) return res.status(500).send();
            return res.status(202).send();
        })
    } catch
        (e) {
        //console.log(e);
        return res.status(500).send();
    }
})
;


// Give information about one user
app.get('/users/:id', function (req, res) {
    try {
        let user_id = req.params.id;
        if (!user_id) return res.status(422).send();
        database.query("SELECT * FROM users WHERE user_id= ? ;", [user_id], function (error, results, fields) {
            if (error) return res.status(404).send();
            if (results[0] === undefined) return res.status(410).send();
            return res.status(200).set('Content-type', 'application/json').send(results[0]);
        });
    } catch (e) {
        return res.status(500).send();
    }
});

// Delete the user with the specified id
app.delete('/users/:id', function (req, res) {
    try {
        let user_id = req.params.id;
        if (!user_id) return res.status(422).send();
        database.query("DELETE FROM users WHERE user_id = ?;", [user_id], function (error, results, fields) {
            if (error) return res.status(404).send();
            return res.status(202).send();
        });
    } catch (e) {
        return res.status(500).send();
    }
});

// Inform the client that this method doesn't exist
app.post('/users/:id', function (req, res) {
    return res.status(405).send();
});

//Modify all attributes of a specified user
app.put('/users/:id', function (req, res) {
    try {
        let user_id = req.params.id;
        if (!user_id) return res.status(422).send();
        let user_first_name = req.body.user_first_name;
        let user_last_name = req.body.user_last_name;
        let user_mail = req.body.user_mail;
        let user_phone = req.body.user_phone;
        let user_postal_code = req.body.user_postal_code;
        let user_address = req.body.user_address;
        let user_city = req.body.user_city;
        let user_password = req.body.user_password;
        let user_image_path = req.body.user_image_path;
        let center_id = req.body.center_id;
        let role_id = req.body.role_id;
        if (!(user_first_name && user_last_name && user_mail && user_phone && user_postal_code && user_address && user_city && user_password && user_image_path && center_id && role_id)) {
            return res.status(422).send();
        }
        database.query("SELECT * FROM users WHERE user_id= ? ;", [user_id], function (error, results, fields) {
            if (error) return res.status(404).send();
            if (results[0] === undefined) return res.status(410).send();
        });
        database.query("UPDATE bde_users_api.users SET user_first_name = ? , user_last_name = ?, user_mail = ? ,user_phone = ? , user_postal_code = ?, user_address = ?, user_city = ? , user_password = ? , user_image_path = ? , modified_at = CURRENT_TIMESTAMP , center_id = ? , role_id = ? WHERE user_id = ?", [user_first_name, user_last_name, user_mail, user_phone, user_postal_code, user_address, user_city, user_password, user_image_path, center_id, role_id, user_id], function (error, results, fields) {
            if (error) return res.status(500).send();
            return res.status(202).send();
        });
    } catch (e) {
        return res.status(500).send();
    }
});

//Patch attributes of the specified user
app.patch('users/:id', function (req, res) {
    try {
        let user_id = req.params.id;
        if (!user_id) return res.status().send();
        database.query("SELECT * FROM users WHERE user_id= ? ;", [user_id], function (error, results, fields) {
            if (error) return res.status(404).send();
            if (results[0] === undefined) return res.status(410).send();
        });
        database.query('START TRANSACTION;', [], function (error, results, fields) {
            if (error) return res.status(500).send();
        });
        let user_first_name = req.body.user_first_name;
        if (user_first_name) {
            database.query('UPDATE bde_users_api.users SET user_first_name= ? WHERE user_id = ? ;', [user_first_name, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let user_last_name = req.body.user_last_name;
        if (user_last_name) {
            database.query('UPDATE bde_users_api.users SET user_last_name= ? WHERE user_id = ? ;', [user_last_name, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let user_mail = req.body.user_mail;
        if (user_mail) {
            database.query('UPDATE bde_users_api.users SET user_mail= ? WHERE user_id = ? ;', [user_mail, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let user_phone = req.body.user_phone;
        if (user_phone) {
            database.query('UPDATE bde_users_api.users SET user_phone= ? WHERE user_id = ? ;', [user_phone, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let user_postal_code = req.body.user_postal_code;
        if (user_postal_code) {
            database.query('UPDATE bde_users_api.users SET user_postal_code= ? WHERE user_id = ? ;', [user_postal_code, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let user_address = req.body.user_address;
        if (user_address) {
            database.query('UPDATE bde_users_api.users SET user_address= ? WHERE user_id = ? ;', [user_address, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let user_city = req.body.user_city;
        if (user_city) {
            database.query('UPDATE bde_users_api.users SET user_city= ? WHERE user_id = ? ;', [user_city, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let user_password = req.body.user_password;
        if (user_password) {
            database.query('UPDATE bde_users_api.users SET  user_password= ? WHERE user_id = ? ;', [user_password, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let user_image_path = req.body.user_image_path
        if (user_image_path) {
            database.query('UPDATE bde_users_api.users SET user_image_path = ? WHERE user_id = ? ;', [user_image_path, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let center_id = req.body.center_id;
        if (center_id) {
            database.query('UPDATE bde_users_api.users SET center_id= ? WHERE user_id = ? ;', [center_id, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        let role_id = req.body.role_id;
        if (role_id) {
            database.query('UPDATE bde_users_api.users SET role_id= ? WHERE user_id = ? ;', [role_id, user_id], function (error, results, fields) {
                if (error) return res.status(422).send();
            });
        }
        database.query('COMMIT;', [], function (error, results, fields) {
            if (error) return res.status(500).send();
            return res.status(202).send();
        })
    } catch
        (e) {
        return res.status(500).send();
    }
})
;

// set port
app.listen(3000, function () {
    console.log('Node app is running on port 3000');
});
module.exports = app;