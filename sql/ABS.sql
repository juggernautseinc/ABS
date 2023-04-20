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

--Changing Names of Visit Category
#IfNotRow2D openemr_postcalendar_categories pc_constant_id office_visit pc_catname Family Therapy
UPDATE openemr_postcalendar_categories SET pc_catname='Family Therapy' where pc_constant_id = 'office_visit';
#EndIf

#IfNotRow2D openemr_postcalendar_categories pc_constant_id comlink_telehealth_established_patient pc_catname Individual Session - 45 Min - Telemedicine
UPDATE openemr_postcalendar_categories SET pc_catname='Individual Session - 45 Min - Telemedicine' where pc_constant_id = 'comlink_telehealth_established_patient';
#EndIf

#IfNotRow2D openemr_postcalendar_categories pc_constant_id established_patient pc_catname Family Therapy W/O Patient - Telemedicine
UPDATE openemr_postcalendar_categories SET pc_catname='Family Therapy W/O Patient - Telemedicine' where pc_constant_id = 'established_patient';
#EndIf

#IfNotRow2D openemr_postcalendar_categories pc_constant_id new_patient pc_catname Individual Session - 60 Min - Telemedicine
UPDATE openemr_postcalendar_categories SET pc_catname='Individual Session - 60 Min - Telemedicine' where pc_constant_id = 'new_patient';
#EndIf

#IfNotRow2D openemr_postcalendar_categories pc_constant_id comlink_telehealth_new_patient pc_catname Individual Session - 30 Min - Telemedicine
UPDATE openemr_postcalendar_categories SET pc_catname='Individual Session - 30 Min - Telemedicine' where pc_constant_id='comlink_telehealth_new_patient';
#EndIf

--Remove unwanted visit category
#IfNotRow2D openemr_postcalendar_categories pc_constant_id preventive_care_services pc_active 0 
UPDATE openemr_postcalendar_categories SET pc_active='0' where pc_constant_id = 'preventive_care_services';
#EndIf

#IfNotRow2D openemr_postcalendar_categories pc_constant_id ophthalmological_services pc_active 0
UPDATE openemr_postcalendar_categories SET pc_active='0' where pc_catid='ophthalmological_services';
#EndIf
