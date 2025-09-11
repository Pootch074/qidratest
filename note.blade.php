@section('content')
<div class="w-full h-screen max-w-[1920px] max-h-[1080px] mx-auto overflow-hidden bg-black">
    <div class="w-full h-[84vh] flex flex-col md:flex-row">
        <!-- Left Panel -->
        <div class="md:w-7/12 w-full bg-gray-800 p-3 flex flex-col h-full overflow-hidden">
            <div id="stepsContainer" class="flex flex-col gap-4 w-full overflow-y-auto">
            </div>

            <div id="noSteps" class="hidden text-white text-lg font-medium mt-4">
                No steps available for your section.
            </div>
        </div>

        <!-- Right Panel -->
        <div class="md:w-5/12 w-full bg-gray-800 text-white p-2 flex flex-col justify-start h-full overflow-hidden">
            <!-- Date & Time -->
            <div class="w-full text-center">
                <p id="current-date" class="text-1xl md:text-2xl font-semibold mb-2"></p>
                <p id="current-time" class="text-2xl md:text-3xl font-bold"></p>
            </div>

            <div class="w-full flex flex-col items-center mt-5 space-y-4">
                <div class="w-full md:w-full">
                    <video id="customVideo" class="w-full rounded-lg shadow-lg max-h-[400px]" autoplay muted loop>
                        <source src="{{ asset('assets/videos/dswd.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="flex flex-col items-center space-y-2 bg-gray-800 bg-opacity-80 p-4 rounded-md w-full">
                    <div class="flex justify-center space-x-4">
                        <button id="volDown" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Vol -</button>
                        <button id="volMute" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Mute</button>
                        <button id="volUp" class="px-2 py-1 bg-gray-700 hover:bg-gray-600 rounded text-white">Vol +</button>
                    </div>
                    <div class="w-full h-2 bg-gray-600 rounded overflow-hidden">
                        <div id="volBar" class="h-full bg-green-500 w-0"></div>
                    </div>
                    <span id="volPercent" class="text-white text-sm font-medium mt-1">0%</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



















































-- Offices Table
CREATE TABLE `offices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `field_office` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_field_office` (`field_office`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Divisions Table
CREATE TABLE `divisions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `division_name` VARCHAR(255) NOT NULL,
  `office_id` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`office_id`) REFERENCES `offices`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sections Table
CREATE TABLE `sections` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_name` VARCHAR(255) NOT NULL,
  `division_id` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`division_id`) REFERENCES `divisions`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Steps Table
CREATE TABLE `steps` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `step_number` INT NOT NULL,
  `step_name` VARCHAR(255) DEFAULT NULL,
  `section_id` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Windows Table
CREATE TABLE `windows` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `window_number` INT NOT NULL,
  `step_id` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`step_id`) REFERENCES `steps`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users Table
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `position` VARCHAR(255) DEFAULT NULL,
  `section_id` BIGINT UNSIGNED DEFAULT NULL,
  `user_type` TINYINT DEFAULT NULL,
  `assigned_category` ENUM('regular','priority') DEFAULT NULL,
  `step_id` BIGINT UNSIGNED DEFAULT NULL,
  `window_id` BIGINT UNSIGNED DEFAULT NULL,
  `status` TINYINT NOT NULL DEFAULT 1,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`step_id`) REFERENCES `steps`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`window_id`) REFERENCES `windows`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transactions Table
CREATE TABLE `transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue_number` INT NOT NULL,
  `client_type` ENUM('priority','regular') NOT NULL,
  `step_id` BIGINT UNSIGNED DEFAULT NULL,
  `window_id` BIGINT UNSIGNED DEFAULT NULL,
  `section_id` BIGINT UNSIGNED DEFAULT NULL,
  `queue_status` ENUM('waiting','pending','serving') NOT NULL DEFAULT 'waiting',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`step_id`) REFERENCES `steps`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`window_id`) REFERENCES `windows`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Videos Table
CREATE TABLE `videos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Marquees Table
CREATE TABLE `marquees` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
