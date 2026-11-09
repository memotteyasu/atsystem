ALTER TABLE `vehicleschedules`
  DROP KEY `idx_vehicleschedules_car_date`,
  DROP COLUMN `carmasters_id`,
  ADD COLUMN `companyvehicles_id` int(11) NOT NULL AFTER `id`,
  ADD KEY `idx_vehicleschedules_vehicle_date` (`companyvehicles_id`, `scheduledate`);
