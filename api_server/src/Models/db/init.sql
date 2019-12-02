CREATE TABLE `Products`(
    `id`	INTEGER NOT NULL,
    `name`	TEXT NOT NULL UNIQUE,
    `description`	TEXT,
    `image`	TEXT NOT NULL,
    `price`	INTEGER NOT NULL
);

