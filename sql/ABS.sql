---adding video link in openemr postcalendar events
#IfMissingColumn openemr_postcalendar_events meeting_link
ALTER TABLE openemr_postcalendar_events add column meeting_link text;
#EndIf

---adding zoom users id in users table
#IfMissingColumn users zoom_user_id
ALTER TABLE users add column zoom_user_id text;
#EndIf

---adding video channel in openemr postcalendar events
#IfMissingColumn openemr_postcalendar_events pc_video_channel
ALTER TABLE openemr_postcalendar_events add column pc_video_channel varchar(255);
#EndIf

--Adding categories for group therapy appointment comments
#IfNotRow categories name Group Theraphy Comments
SET @lft = (SELECT rght FROM  categories where id = 1);
SET @id = (SELECT MAX(id) FROM  categories);
INSERT INTO `categories` (`id`,`name`, `parent`, `lft`, `rght`) values(@id+1, 'Group Theraphy Comments', 1, @lft, @lft+1);
UPDATE `categories` set `rght` = @lft+2 where `id` = 1;
#EndIf

--Adding video channels
#IfNotRow2D list_options list_id lists option_id video_channel
INSERT INTO `list_options` ( `list_id`, `option_id`, `title` ) VALUES ('lists', 'video_channel', 'Video Channels');
#EndIf

#IfNotRow2D list_options list_id video_channel option_id google
INSERT INTO list_options ( list_id, option_id, title, seq) VALUES ('video_channel', 'google', 'Google Meet',1);
#EndIf

#IfNotRow2D list_options list_id video_channel option_id zoom
SET @seq = (SELECT MAX(seq) FROM list_options where list_id="video_channel");
INSERT INTO list_options ( list_id, option_id, title, seq) VALUES ('video_channel', 'zoom', 'Zoom',@seq+1);
#EndIf

