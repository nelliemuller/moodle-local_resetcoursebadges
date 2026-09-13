<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Event observer implementation.
 *
 * @package   local_resetcoursebadges
 * @copyright 2026 Nellie Deutsch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_resetcoursebadges;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/badgeslib.php');

/**
 * Event observers for automatic course badge cleanup.
 */
final class observer {
    /**
     * Removes outdated badge definitions belonging only to the reset course.
     *
     * Issued badges are retained for users through Moodle's archive mode.
     *
     * @param \core\event\course_reset_ended $event
     * @return void
     */
    public static function course_reset_ended(\core\event\course_reset_ended $event): void {
        if (!self::is_enabled()) {
            return;
        }

        $eventdata = $event->get_data();
        $courseid = (int)($eventdata['courseid'] ?? 0);
        self::remove_outdated_course_badges($courseid);
    }

    /**
     * Removes imported badge definitions after a course restore.
     *
     * @param \core\event\course_restored $event
     * @return void
     */
    public static function course_restored(\core\event\course_restored $event): void {
        if (!self::is_enabled()) {
            return;
        }

        $eventdata = $event->get_data();
        $courseid = (int)($eventdata['courseid'] ?? 0);
        self::remove_outdated_course_badges($courseid);
    }

    /**
     * Removes all course badge definitions from one exact reused course.
     *
     * Unissued definitions are fully deleted. Issued definitions are archived,
     * which is Moodle's "delete and keep existing issued badges" operation.
     *
     * @param int $courseid Course id.
     * @return array{deleted: int, archived: int} Cleanup counts.
     */
    public static function remove_outdated_course_badges(int $courseid): array {
        global $DB;

        if ($courseid <= 0 || !$DB->record_exists('course', ['id' => $courseid])) {
            return ['deleted' => 0, 'archived' => 0];
        }

        $badges = $DB->get_records('badge', [
            'type' => BADGE_TYPE_COURSE,
            'courseid' => $courseid,
        ], 'id ASC', 'id');

        $deleted = 0;
        $archived = 0;
        foreach ($badges as $badgerecord) {
            if ($DB->record_exists('badge_issued', ['badgeid' => $badgerecord->id])) {
                $badge = new \core_badges\badge($badgerecord->id);
                // True archives the definition and keeps every issued badge.
                $badge->delete(true);
                $archived++;
                continue;
            }

            $badge = new \core_badges\badge($badgerecord->id);
            // False means fully delete. Moodle's default true only archives
            // the definition, which leaves it visible in badge management.
            $badge->delete(false);
            $deleted++;
        }

        return ['deleted' => $deleted, 'archived' => $archived];
    }

    /**
     * Returns whether automatic cleanup is explicitly enabled.
     *
     * @return bool
     */
    private static function is_enabled(): bool {
        return (bool)get_config('local_resetcoursebadges', 'enabled');
    }
}
