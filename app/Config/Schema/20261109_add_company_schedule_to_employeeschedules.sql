ALTER TABLE `employeeschedules`
  MODIFY `users_id` int(11) DEFAULT NULL,
  ADD COLUMN `kaisyas_id` int(11) DEFAULT NULL AFTER `users_id`,
  ADD KEY `idx_employeeschedules_company_date` (`kaisyas_id`, `scheduledate`);
