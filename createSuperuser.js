const bcrypt = require('bcrypt');
const saltRounds = 10;  // The number of rounds to use for salting

const password = 'Johnny123@@@@';  // The plain-text password

// Hash the password
bcrypt.hash(password, saltRounds, function(err, hashedPassword) {
    if (err) {
        console.error('Error hashing password:', err);
    } else {
        console.log('Hashed password:', hashedPassword);
    }
});
