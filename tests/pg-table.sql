
CREATE TABLE messages (
    id integer NOT NULL,
    title character varying(255),
    message text,
    "time" text
);

CREATE TABLE suivi_news (
    id integer NOT NULL,
    dt_creat character varying(255) NOT NULL,
    nom_log character varying(10) NOT NULL,
    ver_log character varying(10) NOT NULL,
    titre_new character varying(50) NOT NULL,
    url character varying(250) NOT NULL,
    nivutil character varying(25) DEFAULT NULL::character varying
);


ALTER TABLE ONLY messages
    ADD CONSTRAINT messages_pkey PRIMARY KEY (id);

ALTER TABLE ONLY suivi_news
    ADD CONSTRAINT suivi_news_pkey PRIMARY KEY (id);
