require('dotenv').config({ path: '.env.railway' });
const mysql = require('mysql2/promise');

async function testConnection() {
  console.log(`Trying to connect to ${process.env.DB_HOST}:${process.env.DB_PORT} as ${process.env.DB_USERNAME}...`);
  try {
    const connection = await mysql.createConnection({
      host: process.env.DB_HOST,
      port: process.env.DB_PORT,
      user: process.env.DB_USERNAME,
      password: process.env.DB_PASSWORD,
      database: process.env.DB_DATABASE, ssl: { rejectUnauthorized: false },
    });
    console.log('Connected successfully!');
    const [rows, fields] = await connection.execute('SELECT 1 + 1 AS solution');
    console.log('Query result:', rows[0].solution);
    await connection.end();
  } catch (err) {
    console.error('Connection failed:', err.message);
  }
}

testConnection();
