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
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
//MOVE THIS TO AN ENVIRONEMENT VARIABLE
const secret = "942bc48b081cd84a43535096a9d3b4bad2b38749fccd629e475b2ccadb0aef16";

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

// Authentication routes
// Login function
app.post('/api/login', function login(req, res) {
    try {
        console.log('Incomming login request.')
        let email = req.body.user_mail;
        let password = req.body.user_password;
        let user_id = null;
        database.query("SELECT * FROM users WHERE user_mail LIKE ?", [email], function (error, results, fields) {
            if (error) return res.status(404).send();
            if (!results[0]) return res.status(200).set('Content-type', 'application/json').send({
                auth: false,
                token: null
            });
            if (password, results[0].user_password) {
                let token = jwt.sign({
                    user_id: results[0].user_id,
                    user_password: results[0].user_password,
                    user_first_name: results[0].user_first_name,
                    user_last_name: results[0].user_last_name,
                    user_mail: results[0].user_mail,
                    user_phone: results[0].user_phone,
                    user_postal_code: results[0].user_postal_code,
                    user_address: results[0].user_address,
                    user_city: results[0].user_city,
                    user_image_path: results[0].user_image_path,
                    created_at: results[0].created_at,
                    modified_at: results[0].modified_at,
                    center_id: results[0].center_id,
                    role_id: results[0].role_id
                }, secret, {
                    expiresIn: 86400
                });
                return res.status(200).send({auth: true, token: token});
            }
            bcrypt.compare(password, results[0].user_password, function (err, hash) {
                if (error) return res.status(404).send();
                if (!results[0] || hash == false) {
                    return res.status(200).set('Content-type', 'application/json').send({
                        auth: false,
                        token: null
                    })
                } else if (hash == true) {
                    let token = jwt.sign({
                        user_id: results[0].user_id,
                        user_password: results[0].user_password,
                        user_first_name: results[0].user_first_name,
                        user_last_name: results[0].user_last_name,
                        user_mail: results[0].user_mail,
                        user_phone: results[0].user_phone,
                        user_postal_code: results[0].user_postal_code,
                        user_address: results[0].user_address,
                        user_city: results[0].user_city,
                        user_image_path: results[0].user_image_path,
                        created_at: results[0].created_at,
                        modified_at: results[0].modified_at,
                        center_id: results[0].center_id,
                        role_id: results[0].role_id
                    }, secret, {
                        expiresIn: 86400
                    });
                    return res.status(200).send({auth: true, token: token});
                }
            });
        });
    } catch (e) {

        return res.status(500).send();
    }

});
// Logout function
app.get('/api/logout', function (req, res) {
    res.status(200).set('Content-type', 'application/json').send({auth: false, token: null});
});

// /Users routes
// List all users
app.get('/api/users', function usersGet(req, res) {
    try {
        database.query('SELECT *  FROM users;', function (error, results, fields) {
            if (error) return res.status(404).send();
            res.status(200).set('Content-type', 'application/json').send(results);
        });
    } catch (e) {
        return res.status(500).send();
    }
});
// Create a new user
app.post('/api/users', function usersPost(req, res) {
    try {
        let user_first_name = req.body.user_first_name;
        let user_last_name = req.body.user_last_name;
        let user_mail = req.body.user_mail;
        let user_phone = req.body.user_phone;
        let user_postal_code = req.body.user_postal_code;
        let user_address = req.body.user_address;
        let user_city = req.body.user_city;
        let user_password = bcrypt.hashSync(req.body.user_password, 8,);
        let user_image_path = req.body.user_image_path;
        let center_id = req.body.center_id;
        let role_id = 1;
        if (!(user_first_name && user_last_name && user_mail && user_phone && user_postal_code && user_address && user_city && user_password && user_image_path && center_id && role_id)) {
            return res.status(422).send();
        }

        database.query("INSERT INTO bde_users_api.users (user_first_name, user_last_name, user_mail, user_phone, user_postal_code, user_address, user_city, user_password, user_image_path, created_at, modified_at, center_id, role_id) VALUES (?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,?);", [user_first_name, user_last_name, user_mail, user_phone, user_postal_code, user_address, user_city, user_password, user_image_path, center_id, role_id], function (error, results, fields) {
            if (error) return res.status(500).send();
            return res.status(202).send();
        });
    } catch (e) {
        return res.status(500).send();
    }
})
// Inform the client that this method is not allowed
app.delete('/api/users', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.put('/api/users', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.patch('/api/users', function notAllowed(req, res) {
    return res.status(501).send();
});
/* Implemented but not authorized function for anybody
// Remove all users
app.delete('/users', function usersDelete(req, res) {
    try {
        database.query("DELETE FROM users;", function (error, results, fields) {
            if (error) return res.status(404).send();
            return res.status(202).send();
        });
    } catch (e) {
        return res.status(500).send();
    }
});

// Modify multiple user
app.put('/users', function usersPut(req, res) {
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
app.patch('/users', function usersPatch(req, res) {
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

            }
        );

        database.query('COMMIT;', [], function (error, results, fields) {
            if (error) return res.status(500).send();
            return res.status(202).send();
        })
    } catch{
        (e)
        return res.status(500).send();
    }
})
;
*/

// User routes
// Give information about one user
app.get('/api/users/:id', function userGet(req, res) {
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
app.delete('/api/users/:id', function userDelete(req, res) {
    try {
        var token = req.headers['x-access-token'];
        if (!token) {
            return res.status(401).send();
        } else {
            jwt.verify(token, secret, function (err, decoded) {
                let user_id = req.params.id;
                if (!user_id) return res.status(422).send();
                database.query("DELETE FROM users WHERE user_id = ?;", [user_id], function (error, results, fields) {
                    if (error) return res.status(404).send();
                    return res.status(202).send();
                });
            });
        }

    } catch (e) {
        return res.status(500).send();
    }
});
// Inform the client that this method doesn't exist
app.post('/api/users/:id', function notAllowed(req, res) {
    return res.status(501).send();
});
//Modify all attributes of a specified user
app.put('/api/users/:id', function userPut(req, res) {
    try {
        var token = req.headers['x-access-token'];
        if (!token) {
            return res.status(401).send();
        } else {
            jwt.verify(token, secret, function (err, decoded) {
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
                if (!(user_first_name && user_last_name && user_mail && user_phone && user_postal_code && user_address && user_city && user_password && user_image_path && center_id && role_id)) {
                    return res.status(422).send();
                }
                database.query("SELECT * FROM users WHERE user_id= ? ;", [user_id], function (error, results, fields) {
                    if (error) return res.status(404).send();
                    if (results[0] === undefined) return res.status(410).send();
                });
                database.query("UPDATE bde_users_api.users SET user_first_name = ? , user_last_name = ?, user_mail = ? ,user_phone = ? , user_postal_code = ?, user_address = ?, user_city = ? , user_password = ? , user_image_path = ? , modified_at = CURRENT_TIMESTAMP , center_id = ?  WHERE user_id = ?", [user_first_name, user_last_name, user_mail, user_phone, user_postal_code, user_address, user_city, user_password, user_image_path, center_id, user_id], function (error, results, fields) {
                    if (error) return res.status(500).send();
                    return res.status(202).send();
                });
            })
        }
    } catch (e) {
        return res.status(500).send();
    }
});
//Patch attributes of the specified user
app.patch('/api/users/:id', function userPatch(req, res) {
    try {
        var token = req.headers['x-access-token'];
        if (!token) {
            return res.status(401).send();
        } else {
            jwt.verify(token, secret, function (err, decoded) {
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
                let user_image_path = req.body.user_image_path;
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
                database.query('COMMIT;', [], function (error, results, fields) {
                    if (error) return res.status(500).send();
                    return res.status(202).send();
                })
            })
        }
    } catch
        (e) {
        return res.status(500).send();
    }
})
;

// Centers routes
// List all centers
app.get('/api/centers', function usersGet(req, res) {
    try {
        database.query('SELECT *  FROM centers;', function (error, results, fields) {
            if (error) return res.status(404).send();
            res.status(200).set('Content-type', 'application/json').send(results);
        });
    } catch (e) {
        return res.status(500).send();
    }
});
// Create a new centers
app.post('/api/centers', function usersPost(req, res) {
    try {
        let center_location = req.body.center_location;
        database.query("INSERT INTO bde_users_api.centers (center_location) VALUES (?);", [center_location], function (error, results, fields) {
            if (error) return res.status(500).send();
            return res.status(202).send();
        });
    } catch (e) {
        return res.status(500).send();
    }
});
// Inform the client that this method is not allowed
app.delete('/api/centers', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.put('/api/centers', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.patch('/api/centers', function notAllowed(req, res) {
    return res.status(501).send();
});
// Center route
// Give informations on the specified center
app.get('/api/centers/:id', function usersGet(req, res) {
    try {
        let center_id = req.params.id;
        if (!center_id) return res.status(422).send();
        database.query('SELECT *  FROM centers WHERE  center_id = ?;', [center_id], function (error, results, fields) {
            if (error) return res.status(404).send();
            if (results[0] === undefined) return res.status(410).send();
            return res.status(200).set('Content-type', 'application/json').send(results[0]);
        });
    } catch (e) {
        return res.status(500).send();
    }
});
// Create a new centers
app.post('/api/centers/:id', function usersPost(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.delete('/api/centers/:id', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.put('/api/centers/:id', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.patch('/api/centers/:id', function notAllowed(req, res) {
    return res.status(501).send();
});

// Roles routes
// List all roles
app.get('/api/roles', function usersGet(req, res) {
    try {
        database.query('SELECT *  FROM roles;', function (error, results, fields) {
            if (error) return res.status(404).send();
            res.status(200).set('Content-type', 'application/json').send(results);
        });
    } catch (e) {
        return res.status(500).send();
    }
});
// Create a new role
app.post('/api/roles', function usersPost(req, res) {
    try {
        let role_name = req.body.role_name;


        database.query("INSERT INTO bde_users_api.roles(role_name) VALUES (?);", [role_name], function (error, results, fields) {
            if (error) return res.status(500).send();
            return res.status(202).send();
        });
    } catch (e) {
        return res.status(500).send();
    }
});
// Inform the client that this method is not allowed
app.delete('/api/roles', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.put('/api/roles', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.patch('/api/roles', function notAllowed(req, res) {
    return res.status(501).send();
});
// Role routes
// Give informations on one role
app.get('/api/roles/:id', function usersGet(req, res) {
    try {
        let role_id = req.params.id;
        if (!role_id) return res.status(422).send();
        database.query('SELECT *  FROM roles WHERE  roles.role_id = ?;', [role_id], function (error, results, fields) {
            if (error) return res.status(404).send();
            if (results[0] === undefined) return res.status(410).send();
            return res.status(200).set('Content-type', 'application/json').send(results[0]);
        });
    } catch (e) {
        return res.status(500).send();
    }
});
// Create a new centers
app.post('/api/roles/:id', function usersPost(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.delete('/api/roles/:id', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.put('/api/roles/:id', function notAllowed(req, res) {
    return res.status(501).send();
});
// Inform the client that this method is not allowed
app.patch('/api/roles/:id', function notAllowed(req, res) {
    return res.status(501).send();
});
// set port
app.listen(3000, function () {
    console.log('Node app is running on port 3000');
});
module.exports = app;