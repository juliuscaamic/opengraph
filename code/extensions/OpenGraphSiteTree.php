<?php
/**
 * Open graph extension to site tree
 * 
 * @author Julius Caamic <julius@greenbrainer.com>
 * @copyright Copyright (c) 2015, Julius Caamic
 */
class OpenGraphSiteTree extends DataExtension {

	/**
	 * Set open graph properties
	 * 
	 * @var array
	 */
	private static $db = array(
		'OpenGraphTitle' => 'Text',
		'OpenGraphDescription' => 'Text'
	);

	/**
	 * Set open graph image
	 * 
	 * @var array
	 */
	private static $has_one = array(
		'OpenGraphImage' => 'Image'
	);

	/**
	 * Set the fields of editing
	 * 
	 * @param  FieldList $fields
	 */
	public function updateCMSFields(FieldList $fields) {

		$fields->insertAfter(TextField::create('OpenGraphTitle', 'Open Graph Title'), 'ExtraMeta');
		$fields->insertAfter(TextareaField::create('OpenGraphDescription', 'Open Graph Description'), 'OpenGraphTitle');

		$fields->insertAfter(
			UploadField::create('OpenGraphImage', 'Open Graph Image')
			->setFolderName('OpenGraph/' . $this->owner->ClassName), 
			'OpenGraphDescription'
		);
	}

	/**
	 * Extension to add open graph tags
	 * 
	 * @param string &$tags
	 */
	public function MetaTags(&$tags) {
		$baseURL = Director::absoluteBaseURL();
		$link = $baseURL . $this->owner->Link();

		$title = $this->owner->Title;
		$description = DBField::create_field('HTMLText', $this->owner->Content)->LimitWordCount(30);

		if ($this->owner->OpenGraphImage()->ID) {
			$image = $baseURL . $this->owner->OpenGraphImage()->Link();
		} else {
			$image = $baseURL . SiteConfig::current_site_config()->Logo()->Link();
		}

		if ($this->owner->OpenGraphTitle) {
			$title = $this->owner->OpenGraphTitle;
		} else {
			$title = SiteConfig::current_site_config()->Title . ' : ' . $this->owner->Title;
		}

		if ($this->owner->OpenGraphDescription) {
			$description = $this->owner->OpenGraphDescription;
		}

		$tags .= "<meta property=\"og:title\" content=\"{$title}\" />\n";
		$tags .= "<meta property=\"og:description\" content=\"{$description}\" />\n";
		$tags .= "<meta property=\"og:image\" content=\"{$image}\" />\n";
		$tags .= "<meta property=\"og:type\" content=\"Website\" />\n";
		$tags .= "<meta property=\"og:url\" content=\"{$link}\" />\n";

	}
}
