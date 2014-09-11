
CREATE TABLE messages (
    id int(11) NOT NULL AUTO_INCREMENT,
    title varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    message text,
    time text,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;

CREATE TABLE suivi_news (
    id int(11) NOT NULL AUTO_INCREMENT,
    dt_creat varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    nom_log varchar(10) COLLATE utf8_unicode_ci NOT NULL,
    ver_log varchar(10) COLLATE utf8_unicode_ci NOT NULL,
    titre_new varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    url  varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    nivutil varchar(25) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2;
