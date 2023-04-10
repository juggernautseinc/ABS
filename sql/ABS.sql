---adding video link in openemr postcalendar events
#IfMissingColumn openemr_postcalendar_events meeting_link
ALTER TABLE openemr_postcalendar_events add column meeting_link text;
#EndIf


---adding zoom users id in users table
#IfMissingColumn users zoom_user_id
ALTER TABLE users add column zoom_user_id text;
#EndIf
