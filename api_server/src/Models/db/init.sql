CREATE TABLE `Products`(
    `id`	INTEGER NOT NULL,
    `name`	TEXT NOT NULL UNIQUE,
    `description`	TEXT,
    `image_path`	TEXT NOT NULL,
    `price`	INTEGER NOT NULL
);

