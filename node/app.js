const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');
const path = require('path');

const app = express();
app.use(bodyParser.json());

// MySQL connection
const db = mysql.createConnection({
    host: 'sql.daniellinda.net',
    user: 'remote',
    password: 'hm3C4iLL+',
    database: 'crm_dev'
});

db.connect(err => {
    if (err) throw err;
    console.log('Connected to the MySQL database!');
});

// Endpoints
// 1. Retrieve all events
app.get('/events', (req, res) => {
    const query = 'SELECT * FROM events';
    db.query(query, (err, results) => {
        if (err) return res.status(500).send(err);
        res.json(results);
    });
});

// 2. Add a new event
app.post('/events', (req, res) => {
    const { name, description, time_start, time_end } = req.body;
    const query = 'INSERT INTO events (name, description, time_start, time_end) VALUES (?, ?, ?, ?)';
    db.query(query, [name, description, time_start, time_end], (err, result) => {
        if (err) return res.status(500).send(err);
        res.status(201).json({ message: 'Event created!', eventId: result.insertId });
    });
});

// 3. Retrieve a single event by ID
app.get('/events/:event_id', (req, res) => {
    const { event_id } = req.params;
    const query = 'SELECT * FROM events WHERE id = ?';
    db.query(query, [event_id], (err, results) => {
        if (err) return res.status(500).send(err);
        if (results.length === 0) return res.status(404).json({ message: 'Event not found!' });
        res.json(results[0]);
    });
});

// 4. Update an existing event
app.put('/events/:event_id', (req, res) => {
    const { event_id } = req.params;
    const { name, description, time_start, time_end } = req.body;
    const query = 'UPDATE events SET name = ?, description = ?, time_start = ?, time_end = ? WHERE id = ?';
    db.query(query, [name, description, time_start, time_end, event_id], (err, result) => {
        if (err) return res.status(500).send(err);
        if (result.affectedRows === 0) return res.status(404).json({ message: 'Event not found!' });
        res.json({ message: 'Event updated!' });
    });
});

// 5. Delete an event by ID
app.delete('/events/:event_id', (req, res) => {
    const { event_id } = req.params;
    const query = 'DELETE FROM events WHERE id = ?';
    db.query(query, [event_id], (err, result) => {
        if (err) return res.status(500).send(err);
        if (result.affectedRows === 0) return res.status(404).json({ message: 'Event not found!' });
        res.json({ message: 'Event deleted!' });
    });
});

// Start the server
const PORT = 3300;
app.listen(PORT, () => {
    console.log(`Server is running on port http://localhost:${PORT}`);
});