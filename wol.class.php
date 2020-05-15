<?php
class Wol {
	public function create_magic_packet($mac_address) {
		$message = "ffffffffffff";
		$address_bytes = implode("", explode(":", $mac_address));

		$i = 0;
		while ($i != 16) {
			$message .= $address_bytes;
			$i++;
		}

		return hex2bin($message);
	}

	public function send_magic_packet($mac_address, $ip, $port) {
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if ($socket === false) {return false;}
		else {
			$opt = socket_set_option($socket, 1, 6, true);

			if ($opt < 0) {
				return false;
			}

			$message = $this->create_magic_packet($mac_address);
			$address = gethostbyname($ip);
			if (socket_sendto($socket, $message, strlen($message), 0, $address, $port)) {
				socket_close($socket);
				return true;
			}
			else {return false;}
		}
	}
}
?>