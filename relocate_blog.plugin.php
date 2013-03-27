<?php
namespace Habari;

class RelocateBlogPlugin extends Plugin
{
	public function configure()
	{
		$form = new FormUI( 'relocate_blog' );
		$form->append( FormControlText::create('relocate__stub', 'relocate__stub')->label('Stub URL to use for the blog:'));
		$form->append( FormControlSubmit::create('save')->set_caption(_t( 'Save' )));
		return $form;
	}

	public function filter_default_rewrite_rules( $rules )
	{
		$stub = Utils::end_in_slash(Options::get('relocate__stub', ''));
		if($stub == '/') {
			$stub = trim($stub, '/');
		}
		foreach($rules as &$rule) {
			switch($rule['name']) {
				case 'display_entries':
					$rule['parse_regex'] = '#^' . trim($stub, '/') . '(?:/page/(?P<page>[0-9]+))?/?$#';
					$rule['build_str'] = trim($stub, '/') . $rule['build_str'];
					break;
				case 'display_entry':
				case 'display_post':
					$rule['parse_regex'] = preg_replace('#^\#\^#', '#^' . $stub, $rule['parse_regex']);
					$rule['build_str'] = $stub . $rule['build_str'];
					break;
			}
		}
		return $rules;
	}

}

?>