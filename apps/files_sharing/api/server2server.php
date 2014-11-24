<?php
/**
 * ownCloud - OCS API for server-to-server shares
 *
 * @copyright (C) 2014 ownCloud, Inc.
 *
 * @author Bjoern Schiessle <schiessle@owncloud.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Files\Share\API;

class Server2Server {

	/**
	 * create a new share
	 *
	 * @param array $params
	 * @return \OC_OCS_Result
	 */
	public static function createShare() {

		$remote = isset($_POST['remote']) ? $_POST['remote'] : null;
		$token = isset($_POST['token']) ? $_POST['token'] : null;
		$name = isset($_POST['name']) ? $_POST['name'] : null;
		$owner = isset($_POST['owner']) ? $_POST['owner'] : null;
		$shareWith = isset($_POST['shareWith']) ? $_POST['shareWith'] : null;
		$remoteId = isset($_POST['remote_id']) ? $_POST['remote_id'] : null;

		if ($remote && $token && $name && $owner && $remoteId && $shareWith) {

			if(!\OCP\Util::isValidFileName($name)) {
				return new \OC_OCS_Result(null, 400, 'The mountpoint name contains invalid characters.');
			}

			if (!\OCP\User::userExists($shareWith)) {
				return new \OC_OCS_Result(null, 400, 'User does not exists');
			}

			\OC_Util::setupFS($shareWith);

			$mountPoint = \OC\Files\Filesystem::normalizePath('/' . $name);
			$name = \OCP\Files::buildNotExistingFileName('/', $name);

			try {
				\OCA\Files_Sharing\Helper::addServer2ServerShare($remote, $token, $name, $mountPoint, $owner, $shareWith, '', $remoteId);
				return new \OC_OCS_Result();
			} catch (\Exception $e) {
				return new \OC_OCS_Result(null, 500, 'server can not add remote share, ' . $e->getMessage());
			}
		}

		return new \OC_OCS_Result(null, 400, 'server can not add remote share, missing parameter');
	}

	/**
	 * accept server-to-server share
	 *
	 * @param array $params
	 * @return \OC_OCS_Result
	 */
	public static function acceptShare($params) {
		$id = $params['id'];
		// TODO send signal to activity app that server2server share was accepted
	}

	/**
	 * decline server-to-server share
	 *
	 * @param array $params
	 * @return \OC_OCS_Result
	 */
	public static function declineShare($params) {
		$id = $params['id'];
		// TODO send signal to activity app that server2server share was declined
			// TODO remove share from oc_share table
	}

	/**
	 * decline server-to-server share
	 *
	 * @param array $params
	 * @return \OC_OCS_Result
	 */
	public static function unshare($params) {
		$id = $params['id'];
		$owner = isset($_POST['owner']) ? $_POST['owner'] : null;
		$token = isset($_POST['token']) ? $_POST['token'] : null;
		$user = isset($_POST['user']) ? $_POST['user'] : null;
		// TODO send signal to activity app that server2server share was declined
		// TODO remove share from oc_share table
	}

}
