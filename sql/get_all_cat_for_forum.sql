SELECT 
	ca1.category_id, ca1.category, 
    ca2.category_id as parent_id, ca2.category as parent_category,
    ca3.category_id as grandparent_id, ca3.category as grandparent
FROM forum.phpbb_categories as ca1 
LEFT JOIN forum.phpbb_categories as ca2
ON ca2.category_id = ca1.parent_id
LEFT JOIN forum.phpbb_categories as ca3
ON ca3.category_id = ca2.parent_id
WHERE ca1.forum_id = 219
order by category_id, parent_id, grandparent_id;
