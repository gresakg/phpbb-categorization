SELECT 
	cl.category_id, cl.primarna, ca1.parent_id, ca1.category, 
    ca2.category_id as parent_category_id, ca2.category as parent_category, ca2.parent_id as grandparent_id,
    ca3.category_id as grandparent_category_id, ca3.category as grandparent, ca3.parent_id as shouldbezero
FROM forum.phpbb_classification as cl 
LEFT JOIN forum.phpbb_categories as ca1
ON cl.category_id = ca1.category_id  
LEFT JOIN forum.phpbb_categories as ca2
ON ca2.category_id = ca1.parent_id
LEFT JOIN forum.phpbb_categories as ca3
ON ca3.category_id = ca2.parent_id
WHERE topic_id=11000641;