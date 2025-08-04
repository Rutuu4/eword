SELECT * FROM customer_inquiry_followup_record
SELECT * FROM user_p WHERE username LIKE '%ga%'

TRUNCATE customer_inquiry_followup_record

SELECT id,read_status,read_datee,customer_inquiry_status,customer_followupdatee 
FROM inquiry_send 
WHERE `id` = '219558'
ORDER BY read_status DESC

UPDATE inquiry_send SET read_status = ''
UPDATE inquiry_send SET read_datee = NULL
UPDATE inquiry_send SET `customer_inquiry_status` = ''
UPDATE inquiry_send SET `customer_followupdatee` = NULL

SELECT fi.id
FROM inquiry_send AS isq
LEFT JOIN f_inquiry AS fi ON isq.inquiry_id = fi.id
WHERE company_id = '30984' AND  fi.id IS NOT NULL

SELECT company_id FROM inquiry_send GROUP BY company_id 

SELECT * FROM user_p WHERE center IN (SELECT company_id FROM inquiry_send GROUP BY company_id) 

