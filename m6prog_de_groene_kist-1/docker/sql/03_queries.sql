
SELECT *
FROM products
WHERE category_id = 2
ORDER BY name ASC;

SELECT *
FROM products
WHERE category_id = 1
ORDER BY name ASC;

SELECT *
FROM products
WHERE name LIKE '%tom%';

SELECT c.name, COUNT(*) AS aantal_producten
FROM products p
JOIN categories c ON p.category_id = c.id
GROUP BY c.name;
