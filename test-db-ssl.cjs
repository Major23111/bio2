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
    
    try {
        const [rows, fields] = await connection.execute('SELECT id, total_amount, status FROM orders LIMIT 1');
        console.log('Orders query OK');
    } catch (e) {
        console.error('Orders query failed:', e.message);
    }
    
    try {
        const [rows2, fields2] = await connection.execute('SELECT id, name FROM products LIMIT 1');
        console.log('Products query OK');
    } catch (e) {
        console.error('Products query failed:', e.message);
    }
    
    await connection.end();
  } catch (err) {
    console.error('Connection failed:', err.message);
  }
}

testConnection();
