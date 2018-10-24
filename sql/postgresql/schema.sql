-- DROP SEQUENCE IF EXISTS ocopendata_archive_item_s;
CREATE SEQUENCE ocopendata_archive_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

-- DROP TABLE IF EXISTS ocopendata_archive_item;
CREATE TABLE ocopendata_archive_item (
  id integer DEFAULT nextval('ocopendata_archive_item_s'::text) NOT NULL,
  type VARCHAR(50) DEFAULT NULL,  
  class_identifier VARCHAR(100) DEFAULT NULL,  
  data_text TEXT,
  url_alias_list TEXT,  
  node_id_list TEXT,
  object_id INTEGER DEFAULT 0,
  user_id INTEGER DEFAULT 0,
  requested_time INTEGER DEFAULT 0,
  status INTEGER DEFAULT 0
);
CREATE INDEX ocopendata_archive_item_type ON ocopendata_archive_item USING btree (type);
CREATE INDEX ocopendata_archive_item_class_identifier ON ocopendata_archive_item USING btree (class_identifier);
CREATE INDEX ocopendata_archive_item_requested_time ON ocopendata_archive_item USING btree (requested_time);
CREATE INDEX ocopendata_archive_item_user_id ON ocopendata_archive_item USING btree (user_id);
ALTER TABLE ONLY ocopendata_archive_item ADD CONSTRAINT ocopendata_archive_item_pkey PRIMARY KEY (id);
