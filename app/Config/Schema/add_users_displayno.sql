ALTER TABLE `users`
  ADD COLUMN `displayno` int(11) NOT NULL DEFAULT 0 COMMENT '表示順' AFTER `syain_cd`;

UPDATE `users`
SET `displayno` = `syain_cd`
WHERE `displayno` = 0
  AND `syain_cd` IS NOT NULL;
