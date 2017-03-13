--
-- Table structure for table galette_QRcodes
--
DROP TABLE IF EXISTS galette_QRcodes;
CREATE TABLE galette_QRcodes (
  id_adh integer,
  tel VARCHAR(255),
  mail VARCHAR(255),
  Passage_de_grades VARCHAR(255),
  PRIMARY KEY (id_adh)
);

