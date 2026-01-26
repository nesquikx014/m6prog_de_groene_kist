
SELECT *
FROM products
WHERE category = 'Fruit'
ORDER BY name ASC;


SELECT *
FROM products
WHERE category = 'Groente'
ORDER BY name ASC;


SELECT *
FROM products
WHERE name LIKE '%tom%';



SELECT category, COUNT(*) AS aantal_aanbiedingen
FROM offers
GROUP BY category;
