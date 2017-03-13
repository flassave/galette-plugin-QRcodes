--
-- Table structure for table `galette_QRcodes`
--

DROP TABLE IF EXISTS galette_QRcodes;
CREATE TABLE galette_QRcodes (
  id_adh int(10) unsigned NOT NULL,
  tel VARCHAR(255),
  mail VARCHAR(255),
  Passage_de_grades VARCHAR(255),
  PRIMARY KEY (id_adh)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

