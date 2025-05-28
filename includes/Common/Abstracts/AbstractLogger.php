<?php

	namespace Iyzico\IyzipayWoocommerce\Common\Abstracts;

	use Iyzico\IyzipayWoocommerce\Common\Interfaces\LoggerInterface;

	abstract class AbstractLogger implements LoggerInterface
	{
		protected const INFO_LOG = 'iyzico_info.log';
		protected const ERROR_LOG = 'iyzico_error.log';
		protected const WARN_LOG = 'iyzico_warn.log';
		protected const WEBHOOK_LOG = 'iyzico_webhook.log';
		protected $logDir;

		public function __construct(string $logDir = '')
		{
			$this->logDir = $logDir ?: PLUGIN_PATH . '/log_files/';
			$this->ensureLogDirectoryExists();
		}

		protected function ensureLogDirectoryExists(): void
		{
			global $wp_filesystem;

			if (empty($wp_filesystem)) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				$access_type = get_filesystem_method();

				if ($access_type === 'direct') {
					WP_Filesystem();
				} else {
					// Fallback to direct file operations if WP_Filesystem is not available
					if (!file_exists($this->logDir)) {
						wp_mkdir_p($this->logDir);
						$this->createHtaccessDirect();
					}
					return;
				}
			}

			if (!$wp_filesystem->is_dir($this->logDir)) {
				$wp_filesystem->mkdir($this->logDir, 0755);
				$this->createHtaccess();
			}
		}

		protected function createHtaccess(): void
		{
			global $wp_filesystem;

			$htaccessContent = "Deny from all\n";
			$filePath        = trailingslashit($this->logDir) . '.htaccess';

			$wp_filesystem->put_contents($filePath, $htaccessContent, FS_CHMOD_FILE);
		}

		protected function createHtaccessDirect(): void
		{
			$htaccessContent = "Deny from all\n";
			$filePath        = trailingslashit($this->logDir) . '.htaccess';
			file_put_contents($filePath, $htaccessContent);
		}

		abstract public function info(string $message);

		abstract public function error(string $message);

		abstract public function warn(string $message);

		abstract public function webhook(string $message);

		protected function log(string $file, string $level, string $message)
		{
			$timestamp  = gmdate('Y-m-d H:i:s');
			$logMessage = "[$timestamp] [$level] $message" . PHP_EOL;

			$filePath = $this->logDir . $file;
			file_put_contents($filePath, $logMessage, FILE_APPEND | LOCK_EX);
		}
	}
