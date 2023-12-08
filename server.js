// const express = require('express');
// const mysql = require('mysql');

// const app = express();
// const port = 3000;

// const connection = mysql.createConnection({
//   host: 'localhost',
//   user: 'root',
//   password: '',
//   database: 'location'
// });

// app.get('/get_all_locations', (req, res) => {
//   const query = 'SELECT user_id, latitude, longitude FROM location ORDER BY timestamp ASC';
  
//   connection.query(query, (error, results) => {
//     if (error) throw error;

//     res.json({ locations: results });
//   });
// });

// app.listen(port, () => {
//   console.log(`Server is running on port ${port}`);
// });
