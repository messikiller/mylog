/**
 * created by nessikiller
 * mylog抽象日志对象
 */
class myLog
{
	/**
	 * @var logpath string 日志文件存放的目录
	 */
	private $logpath;

	/*
	 * @var file_handler resource 日志文件读写操作句柄
	 */
	private $file_handler;
	
	/**
	 * 初始化对象，设置日志目录为默认目录，打开文件句柄
	 */
	function __construct()
	{
		//设置日志目录为默认目录
		$this->logpath = dirname(__FILE__).'/../logs/';
		$filename = $this->logpath . $this->_get_current_date() . '.log';
		//打开文件句柄
		$this->file_handler = fopen($filename, 'a+');
		return true;
	}

	/**
	 * 销毁文件句柄后销毁对象
	 */
	function __destruct()
	{
		if ( $this->file_handler=='NULL' ) {
			return true;
		}

		fclose($this->file_handler);
		return true;
	}

	/**
	 * 以时分秒格式获取当前时间
	 * @return string 详细时间
	 */
	private function _get_current_time()
	{
		$time=date("H:m:i", time());
		return $time;
	}

	/**
	 * 以年月日格式获取当前系统时间
	 * @return string 粗略时间
	 */
	private function _get_current_date()
	{
		$time=date("Y-m-d", time());
		return $time;
	}

	/**
	 * 获取访问者IP地址
	 * @return string 访问者IP地址
	 */
	private function _get_client_ip()
	{
		$ip=getenv("REMOTE_ADDR");
		$ip= $ip=='::1' ? '127.0.0.1' : $ip;
		return $ip;

	}

	/**
	 * 获取访问者使用的浏览器类型
	 * @return string 浏览器描述详细信息
	 */
	private function _get_client_browser()
	{
		$browser = getenv("HTTP_USER_AGENT");
		return $browser;
	}

	/**
	 * 以特定格式将传入的字符串格式化
	 * @param  string $content 要写入日志的信息
	 * @param  string $level   日志记录级别
	 * @return string          格式化后的字符串
	 */
	private function _format_row_record($content='NULL', $level='info')
	{
		$time = $this->_get_current_time();
		$browser = $this->_get_client_browser();
		$divider = ' | ';
		$client_ip = $this->_get_client_ip();
		$row = $time . $divider . $level . $divider . $client_ip . $divider . $content . $divider . $browser . "\r\n";
		return $row;
	}

	/**
	 * 设置日志存放的目录
	 * @param string $dir 日志存放的目录
	 */
	public function set_log_dir($dir)
	{
		if ( !isset($dir) )
		{
			echo "设置日志目录失败！";
			return false;
		}

		if ( !file_exists($dir) ) 
		{
			echo "指定的日志目录不存在！";
			return false;
		}

		//如果参数字符串不是以'\'结尾，则添加'\'
		if ( preg_match('/^.*[^\/]$/', $dir) ) 
		{
			$dir=$dir.'/';
		}

		$this->logpath=$dir;

		//关闭旧句柄，打开新操作句柄
		fclose($this->file_handler);
		$filename = $this->logpath . $this->_get_current_date() . '.log';
		$this->file_handler = fopen($filename, 'a+');

		return true;
	}

	/**
	 * 记录一条日志信息
	 * @param  string $content 要记录的信息
	 * @param  string $level   要记录的日志级别
	 * @return boolean
	 */
	public function record($content='NULL', $level='info')
	{
		$row_str = $this->_format_row_record($content, $level);
		fwrite($this->file_handler, $row_str);
		return true;
	}
	
	/**
	 * 记录一条info级别的日志信息
	 * @param  string $content 要记录的信息
	 * @return boolean
	 */
	public function info($content='NULL')
	{
		$level='info';
		$row_str = $this->_format_row_record($content, $level);
		fwrite($this->file_handler, $row_str);
		return true;
	}

	/**
	 * 记录一条error级别的日志信息
	 * @param  string $content 要记录的信息
	 * @return boolean
	 */
	public function error($content='NULL')
	{
		$level='error';
		$row_str = $this->_format_row_record($content, $level);
		fwrite($this->file_handler, $row_str);
		return true;
	}

	/**
	 * 记录一条success级别的日志信息
	 * @param  string $content 要记录的信息
	 * @return boolean
	 */
	public function success($content='NULL')
	{
		$level='success';
		$row_str = $this->_format_row_record($content, $level);
		fwrite($this->file_handler, $row_str);
		return true;
	}
}