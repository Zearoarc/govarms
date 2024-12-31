const express = require('express');
const app = express();
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'arms_db'
});

app.get('/api/data', (req, res) => {
    connection.query('SELECT stat FROM request_status', (error, results) => {
        if (error) throw error;
        res.json(results);
    });
});

app.listen(3000, () => {
    console.log('Server running on port 3000');
});