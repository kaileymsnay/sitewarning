<?php
/**
 *
 * Site Warning extension for the phpBB Forum Software package
 *
 * @copyright (c) 2021, Kailey Snay, https://www.snayhomelab.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kaileymsnay\sitewarning\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Site Warning event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config      $config
	 * @param \phpbb\template\template  $template
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template)
	{
		$this->config = $config;
		$this->template = $template;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'	=> 'load_language',
			'core.page_header'	=> 'page_header',

			'core.acp_board_config_edit_add'	=> 'acp_board_config_edit_add',
		];
	}

	/**
	 * Load common language files
	 */
	public function load_language($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'kaileymsnay/sitewarning',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function page_header($event)
	{
		$this->template->assign_vars([
			'SITE_WARNING'	=> $this->config['site_warning'],
		]);
	}

	public function acp_board_config_edit_add($event)
	{
		if ($event['mode'] == 'settings')
		{
			$config_vars = [
				'site_warning'	=> ['lang' => 'SITE_WARNING', 'validate' => 'string', 'type' => 'text:40:255', 'explain' => true],
			];

			$event->update_subarray('display_vars', 'vars', phpbb_insert_config_array($event['display_vars']['vars'], $config_vars, ['after' => 'board_index_text']));
		}
	}
}
