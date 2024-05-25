CREATE DATABASE hw1_complete;
USE hw1_complete;

CREATE TABLE images (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
    section VARCHAR(255),
    content JSON
);

CREATE TABLE tokens (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL
);

CREATE TABLE users (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    genere VARCHAR(255) NOT NULL
);

CREATE TABLE cookies (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	user_id INTEGER NOT NULL,
    hash VARCHAR(255) NOT NULL,
    expires INTEGER NOT NULL,
    INDEX idx_user (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE collections (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	num_like INTEGER DEFAULT 0,
    category VARCHAR(255) NOT NULL,
    content JSON
);

CREATE TABLE favorites (
	user_id INTEGER NOT NULL,
    collection_id INTEGER NOT NULL,
    PRIMARY KEY (user_id, collection_id),
    INDEX idx_user (user_id),
    INDEX idx_collection (collection_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (collection_id) REFERENCES collections(id)
);

CREATE TABLE auctions (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    foto VARCHAR(255) NOT NULL,
    titolo VARCHAR(255) NOT NULL,
    durata DATETIME NOT NULL,
    prezzo_iniziale FLOAT NOT NULL,
    num_offerte INTEGER DEFAULT 0, 
    ultimo_prezzo INTEGER DEFAULT 0, 
	INDEX idx_user (user_id),
	FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE offers(
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    auction_id INTEGER NOT NULL,
    prezzo FLOAT, 
	INDEX idx_user (user_id),	
    INDEX idx_auction (auction_id),
	FOREIGN KEY (user_id) REFERENCES users(id), 
	FOREIGN KEY (auction_id) REFERENCES auctions(id) ON DELETE CASCADE
);

CREATE TABLE notifications(
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    content VARCHAR(255),
    INDEX idx_user (user_id),	
    FOREIGN KEY (user_id) REFERENCES users(id)
);

DELIMITER //
CREATE TRIGGER likes
AFTER INSERT ON favorites
FOR EACH ROW
BEGIN
UPDATE collections
SET num_like = num_like + 1
WHERE id = new.collection_id;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER unlikes
AFTER DELETE ON favorites
FOR EACH ROW
BEGIN
UPDATE collections
SET num_like = num_like - 1
WHERE id = old.collection_id;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_offers
AFTER INSERT ON offers
FOR EACH ROW
BEGIN
UPDATE auctions
SET num_offerte = num_offerte + 1
WHERE id = new.auction_id;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER update_price
AFTER INSERT ON offers
FOR EACH ROW
BEGIN
UPDATE auctions
SET ultimo_prezzo = new.prezzo
WHERE id=new.auction_id;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER check_date
BEFORE INSERT ON offers
FOR EACH ROW
BEGIN
IF(now() > (SELECT durata FROM auctions WHERE id=new.auction_id)) THEN
	SIGNAL SQLSTATE '45000'
	SET MESSAGE_TEXT = 'Impossibile inserire offerta: l\'asta è scaduta';
END IF;
END //
DELIMITER ;


DELIMITER //
CREATE TRIGGER check_price
BEFORE INSERT ON offers
FOR EACH ROW
BEGIN
/*o analogamente IF(new.prezzo < (SELECT prezzo FROM offers WHERE auction_id=new.auction_id ORDER BY prezzo DESC LIMIT 1)) THEN*/
IF(new.prezzo < (SELECT ultimo_prezzo FROM auctions WHERE id=new.auction_id) || new.prezzo < (SELECT prezzo_iniziale FROM auctions WHERE id=new.auction_id)) THEN
	SIGNAL SQLSTATE '45000'
	SET MESSAGE_TEXT = 'Impossibile inserire offerta: il prezzo deve essere maggiore dell\'ultimo prezzo';
END IF;
END //
DELIMITER ;

INSERT INTO collections (category, content) VALUES ('Painting and Sculpture', '{"image" : "images/collection1.jpg" , "author" : "Piet Mondrian" , "name" : "Composition with Red, Blue, Black, Yellow, and Gray" , "period" : "1921" , "location" : "MoMA, Floor 5, 513" , "credit" : "Gift of John L. Senior, Jr." , "dimension" : "29 7/8 x 20 5/8 (76 x 52.4 cm)" , "description" : "Oil on canvas" }');
INSERT INTO collections (category, content) VALUES ('Painting and Sculpture', '{"image" : "images/collection2.jpg" , "author" : "Jackson Pollock" , "name" : "The She-Wolf" , "period" : "1943" , "location" : "MoMA, Floor 5, 522", "credit" : "Purchase", "dimension": "41 7/8 x 67 (106.4 x 170.2 cm)" , "description" : "In the early 1940s Pollock, like many of his peers, explored primeval or mythological themes in his work. The wolf in this painting may allude to the animal that suckled the twin founders of Rome, Romulus and Remus, in the myth of the city’s birth. But “She-Wolf came into existence because I had to paint it,” Pollock said in 1944. In an attitude typical of his generation, he added, “Any attempt on my part to say something about it, to attempt explanation of the inexplicable, could only destroy it.” The She-Wolf was featured in Pollock’s first solo exhibition, at Art of This Century gallery in New York in 1943. MoMA acquired the painting the following year, making it the first work by Pollock to enter a museum collection." }');
INSERT INTO collections (category, content) VALUES ('Media and Performance', '{"image" : "images/collection3.jpg" , "author" : "Agosto Machado" , "name" : "Shrine (White)" , "period" : "2022" , "location" : "MoMA, Floor 2, 202", "credit" : "Acquired through the generosity of Scott Lorinsky" , "dimension" : "91 1/2 × 36 × 10 (232.4 × 91.4 × 25.4 cm)" ,"description" : "Downtown New York has hosted generations of underground cultural communities, providing a vibrant home for drag queens, theater performers, filmmakers, and outcasts. Performance artist and queer liberation activist Machado has been a long-standing figure in these scenes. Over five decades, he’s amassed a large collection of art and ephemera from the city’s counterculture, which he assembles into shrines. Many of these objects memorialize losses he has experienced—some due to street violence against queer folks, others due to federally sanctioned negligence during the AIDS crisis. “It’s really ancestor worship, my gratitude for all these people who came through my life,” Machado has explained." }');
INSERT INTO collections (category, content) VALUES ('Architecture and Design', '{"image" : "images/collection4.jpg" , "author" : "Kazuo Kawasaki" , "name" : "Carna Folding Wheelchair" , "period" : "1989" , "location" : "MoMA, Floor 4, 417" , "credit" : "Gift of the designer" , "dimension" : "33 x 22 x 35 1/4 (83.8 x 55.9 x 89.5 cm)" , "description" : "Kazuo Kawasaki, the designer of this object, became a wheelchair user following an accident. He said, “I want to design products for myself first.” Kawasaki used lightweight metals like titanium and aluminum to make this wheelchair. He wanted to make it easier to travel with. What objects help you move around? Tell a friend or family member your favorite things about those objects." }');
INSERT INTO collections (category, content) VALUES ('Architecture and Design', '{"image" : "images/collection5.jpg" , "author" : "Le Corbusier with Pierre Jeanneret" , "name" : "Les Terrasses, Villa Stein-de-Monzie, Garches, France" , "period" : "1926–1928 (model 1970)" , "location" : "MoMA, Floor 5, 513" , "credit" : "Lily Auchincloss Fund" , "dimension" : "35 1/4 × 35 1/4 × 20 7/8 (89.5 × 89.5 × 53 cm) Vitrine: 30 × 45 × 45 (76.2 × 114.3 × 114.3 cm) Tray: 3 × 45 × 45 (7.6 × 114.3 × 114.3 cm)" , "description" : "Acrylic, wood, metal and paint" }');
INSERT INTO collections (category, content) VALUES ('Architecture and Design', '{"image" : "images/collection6.jpg" , "author" : "Hector Guimard" , "name" : "Entrance Gate to Paris Subway (Métropolitain) Station, Paris, France" , "period" : "c. 1900" , "location" : "MoMA, Floor 1, Sculpture Garden" , "credit" : "Gift of Régie Autonome des Transports Parisiens" , "dimension" : "13 11 x 17 10 x 32 (424.2 x 543.6 x 81.3 cm)" , "description" : "The emergence of the Art Nouveau style toward the end of the nineteenth century resulted from a search for a new aesthetic that was not based on historical or classical models. The sinuous, organic lines of Guimard’s design and the stylized, giant stalks drooping under the weight of what seem to be swollen tropical flowers, but are actually amber glass lamps, make this a quintessentially Art Nouveau piece. Guimard’s designs for this famous entrance arch and two others were intended to visually enhance the experience of underground travel on the new subway system for Paris. Paris was not the first city to implement an underground train system (London already had one), but the approaching Paris Exposition of 1900 accelerated the need for an efficient and attractive means of mass transportation. Although Guimard never formally entered the competition for the design of the system’s entrance gates, launched in 1898 by the Compagnie du Métropolitain, he won the commission with his avant-garde schemes, which all employed standardized cast-iron components to facilitate manufacture, transport, and assembly. While Parisians were at first hesitant in their response to Guimard’s use of a vocabulary associated with luxury jewelry and domestic furnishings, the Métro gates, installed throughout the city, effectively brought the Art Nouveau style into the realm of popular culture." }');
INSERT INTO collections (category, content) VALUES ('Media and Performance', '{"image" : "images/collection7.jpg" , "author" : "Joan Jonas" , "name" : "Volcano Saga" , "period" : "1989" , "location" : "MoMA, Floor 6" , "credit" : "Purchase" , "dimension" : "" , "description" : "Searching for new narrative methods, Jonas first developed Volcano Saga in 1985 after visiting Iceland with video artist Steina Vasulka. The performance is an interpretation of the Laxdaela Saga, a thirteenth-century Icelandic folktale about a woman and her four dreams. In 1989 Jonas adapted the story into a video featuring actors Tilda Swinton and Ron Vawter. In the video Swinton and Vawter appear superimposed over images of the Icelandic landscape—which itself becomes a kind of character. Subsequently developed into an installation, Volcano Saga was in Jonas’s words, the beginning of my synthesizing the development of the female character, the story as a mirror, and the volcanic landscapes as representation of narrative." }');
INSERT INTO collections (category, content) VALUES ('Painting and Sculpture', '{"image" : "images/collection8.jpg" , "author" : "Faith Ringgold" , "name" : "American People Series #20: Die" , "period" : "1967" , "location" : "MoMA, Floor 4, 415" , "credit" : "Acquired through the generosity of The Modern Womens Fund, Ronnie F. Heyman, Glenn and Eva Dubin, Lonti Ebers, Michael S. Ovitz, Daniel and Brett Sundheim, and Gary and Karen Winnick" , "dimension" : "72 × 144 (182.9 × 365.8 cm)" , "description" : "Recalling her motivation for making this work, Ringgold has explained, “I became fascinated with the ability of art to document the time, place, and cultural identity of the artist. How could I, as an African American woman artist, document what was happening around me?” Ringgold’s American People Series confronts race relations in the United States in the 1960s. This mural-sized painting evokes the civil uprisings erupting around the country at the time. On the canvas, blood spatters evenly across an interracial group of men, women, and children, suggesting that no one is free from this struggle. Their clothing—smart dresses and business attire—implies that a well-off professional class is being held accountable in this scene of violent chaos. Ringgold has allied herself with a range of artists who took contemporary violence as their subject, from Jacob Lawrence to Pablo Picasso. In particular, Die’s scale, composition, and abstract background explicitly refer to Picasso’s Guernica: Ringgold studied that monumental 1937 depiction of the tragedies of war at MoMA when the painting was on long-term loan there from 1939 to 1981. Even as she was looking back, however, Ringgold was also looking ahead: “I was . . . terrified because I saw Die as a prophecy of our times.” The children grasping each other near the center of the painting give form to this fear of the future." }');
INSERT INTO collections (category, content) VALUES ('Painting and Sculpture', '{"image" : "images/collection9.jpg" , "author" : "Andy Warhol" , "name" : "Campbell Soup Cans" , "period" : "1962" , "location" : "MoMA, Floor 4, 412" , "credit" : "Partial gift of Irving Blum Additional funding provided by Nelson A. Rockefeller Bequest, gift of Mr. and Mrs. William A. M. Burden, Abby Aldrich Rockefeller Fund, gift of Nina and Gordon Bunshaft, acquired through the Lillie P. Bliss Bequest, Philip Johnson Fund, Frances R. Keech Bequest, gift of Mrs. Bliss Parkinson, and Florence B. Wesley Bequest (all by exchange)" , "dimension" : "Each canvas 20 x 16 (50.8 x 40.6cm). Overall installation with 3 between each panel is 97 high x 163 wide" , "description" : "When asked why he chose to paint Campbell’s soup cans, Warhol offered a deadpan reply: “I used to have the same lunch every day, for twenty years, I guess, the same thing over and over again.” That daily meal is the subject of this work consisting of thirty-two canvases—one for each of the flavors then sold by Campbell’s—using a combination of projection, tracing, painting, and stamping. Repeating the nearly identical image, the canvases at once stress the uniformity and ubiquity of the product’s packaging and subvert the idea of painting as a medium of invention and originality." }');
INSERT INTO collections (category, content) VALUES ('Drawings and Prints', '{"image" : "images/collection10.jpg" , "author" : "Brice Marden" , "name" : "Drawing 2 Hydra" , "period" : "1986" , "location" : "MoMA, Floor 4, 407" , "credit" : "Gift of Mr. & Mrs. Maxime L. Hermanos (by exchange)" , "dimension" : "19 1/2 x 15 1/8 (49.5 x 38.5 cm)" , "description" : "Gouache and ink on paper" }');
INSERT INTO collections (category, content) VALUES ('Drawings and Prints', '{"image" : "images/collection11.jpg" , "author" : "Ellsworth Kelly" , "name" : "Study for Meschers" , "period" : "1951" , "location" : "MoMA, Floor 4, 416" , "credit" : "Gift of the artist" , "dimension" : "19 1/2 x 19 1/2 (49.5 x 49.5 cm)" , "description" : "Cut-and-pasted printed paper" }');

INSERT INTO images (section, content) VALUES ('exibition', '{"image" : "images/section_s1.webp" , "title" : "An-My Le" , "text" : "Member Last Look, Mar17 Last chance Through Mar16", "image_dinamic" : "images/s1_js.jpg"}');
INSERT INTO images (section, content) VALUES ('exibition', '{"image" : "images/section_s2.jpg" , "title" : "Crafting Modernity: Design in Latin America, 1940-1980" , "text" : "Through Sep 22", "image_dinamic" : "images/s2_js.jpg"}');
INSERT INTO images (section, content) VALUES ('exibition', '{"image" : "images/section_s3.jpg" , "title" : "Joan Jonas: Good Night Good Morning" , "text" : "Member Previews, Mar14-16 Mar17-Jul6, 2024", "image_dinamic" : "images/s3_js.jpg"}');
INSERT INTO images (section, content) VALUES ('exibition', '{"image" : "images/section_s4.jpg" , "title" : "Shana Moulton: Meta/Physical Therapy" , "text" : "Through Apr 21", "image_dinamic" : "images/s4_js.jpg"}');
INSERT INTO images (section, content) VALUES ('exibition', '{"image" : "images/section_s5.jpg" , "title" : "Grace Wales Bonner" , "text" : "Now on view", "image_dinamic" : "images/s5_js.avif"}');
INSERT INTO images (section, content) VALUES ('exibition', '{"image" : "images/section_s6.jpg" , "title" : "Body Constructs" , "text" : "What happens when design collides with real people?", "image_dinamic" : "images/s6_js.jpg"}');
INSERT INTO images (section, content) VALUES ('exibition', '{"image" : "images/section_s7.jpg" , "title" : "Read, watch, and listen from wherever you are." , "text" : "Explore Magazine Online", "image_dinamic" : "images/s7_js.jpg"}');
INSERT INTO images (section, content) VALUES ('exibition', '{"image" : "images/section_s8.jpg" , "title" : "Visit MoMA PS1 in Queens" , "text" : "Free for New Yorkers MoMA PS1", "image_dinamic" : "images/s8_js.webp"}');
INSERT INTO images (section, content) VALUES ('magazine', '{"image" : "images/magazine2.jpg", "title1": "Drawn to MoMA", "title2" : "Kristen Radtke’s Pregnancy in Art", "description" : "The illustrator reflects on her journey to motherhood and how it changed her relationship to art.", "author_and_date" : "Kristen Radtke May 8, 2024"}');
INSERT INTO images (section, content) VALUES ('magazine', '{"image" : "images/magazine.jpeg", "title1": "Drawn to MoMA", "title2" : "Manjit Thapp Thaw Out", "description" : "The illustrator helps us look beyound our winter blues.", "author_and_date" : "Manjit Thapp Mar13, 2024"}');
INSERT INTO images (section, content) VALUES ('sponsor', '{"image" : "images/sponsor1.jpg"}');
INSERT INTO images (section, content) VALUES ('sponsor', '{"image" : "images/sponsor2.jpg"}');
INSERT INTO images (section, content) VALUES ('sponsor', '{"image" : "images/sponsor3.jpg"}');
INSERT INTO images (section, content) VALUES ('sponsor', '{"image" : "images/sponsor4.jpg"}');

SELECT * FROM favorites;
SELECT * FROM tokens;
SELECT * FROM cookies;
SELECT * FROM auctions;