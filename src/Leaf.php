<?php
namespace LILPLP\IBA;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use ParsedownExtra;

use Symfony\Component\Yaml\Yaml;

class Leaf {
	public $type;
    public $uri;
    protected $slug;
    public $base;
    
    function __construct($uri)
    {
	    $this->uri = $this->exists($uri);
// 	    dd($this->uri);
	    if ( $this->uri )
	    {
		    $uri_parts = explode('/', $uri);
			$uri_base = $uri_parts[0] . '/index.md';
			$this->base = $this->retriveMetas($uri_base, 'leaves');
			$this->base['slug'] = $uri_parts[0];
			$this->menu = $this->constructMenu($uri_parts[0]);
	    }
	    return $this->uri;
    }
    
    public function exists($uri)
	{
		$file = rtrim($uri,'/') . ".md";
		$directory = $uri . "/index.md";
		$asset = $uri;
		if ( Storage::disk('leaves')->exists($file) ) {
			$this->type = "file";
			return $file;
		}
		if ( Storage::disk('leaves')->exists($directory) ) {
			$this->type = "directory";
			return $directory;
		}
		if ( Storage::disk('leaves')->exists($asset) ) {
			$this->type = "asset";
			return $asset;
		}
		return false;
	}
	
	public function cssStandards($content)
	{
		if ( !empty(config('iba.short_keys')) )
		{
			$cssStandards = config('iba.short_keys');
	        $keys = $values = array();
	        foreach ($cssStandards as $key => $value) {
			    $keys[] = '%' . $key . '%';
			    $values[] = $value;
	        }
	        return str_replace($keys, $values, $content);
        }
        return $content;
	}
    
    
    public function render($file, $disk = 'seed')
	{
	    $leaf = $this->retriveMetas($file, $disk);
	    $rawContent = Storage::disk($disk)->get($file);
	    $leaf['slug'] = $this->slug;
	    
	    $metaHeaderPattern = "/^(\/(\*)|---)[[:blank:]]*(?:\r)?\n"
	    . "(?:(.*?)(?:\r)?\n)?(?(2)\*\/|---)[[:blank:]]*(?:(?:\r)?\n|$)/s";
	    $content = preg_replace($metaHeaderPattern, '', $rawContent, 1);
	    $content_css = $this->cssStandards($content);
		$extra = new ParsedownExtra();
		$leaf['content'] = $extra->text($content_css);
	    return $leaf;
	}
	
	public function retriveMetas($file, $disk)
	{
		$rawContent = Storage::disk($disk)->get($file);
	    $pattern = "/^(\/(\*)|---)[[:blank:]]*(?:\r)?\n"
	 . "(?:(.*?)(?:\r)?\n)?(?(2)\*\/|---)[[:blank:]]*(?:(?:\r)?\n|$)/s";
	    if (preg_match($pattern, $rawContent, $rawMetaMatches) && isset($rawMetaMatches[3]))
	    {
	        $metas = Yaml::parse($rawMetaMatches[3]);
	        $metas = ($metas !== null) ? array_change_key_case($metas, CASE_LOWER) : array();
	    }
	    return $metas;
	}
	
	public function content() {
		
		$leaf = $this->render($this->uri, 'leaves');
		$base = $this->base;
		return 	response()->json($leaf);
	}
	
	
	public function show()
	{
		switch ($this->type) {
			case "directory":
			case "file":
				return $this->display();
				break;
			case "asset":
				$asset = Storage::disk('leaves')->get($this->uri);
				$asset_name = last(explode('/', $this->uri));
				return response()->asset($asset_name, $this->uri, 'leaves');
				break;
		}
	}
	
	public function display() {
		$leaf = $this->render($this->uri, 'leaves');
		$base = $this->base;
		$menu = $this->menu;
		if ( array_key_exists('style', $leaf) )
		{
			$blade_file = 'leaf.' . $leaf['style'];
			
			if ( $leaf['style'] != 'index' ) {
				return $this->leafReturn($blade_file, compact('leaf', 'base', 'menu'), '200');
			}
			return $this->leafReturn('iba::leaf', compact('leaf', 'base', 'menu'), '200'); /// to be changed later !!!
		}
		return 	$this->leafReturn('iba::leaf', compact('leaf', 'base', 'menu'), '200');
	}
	
	public function leafReturn($view, $function)
	{
		if ( !request()->wantsJson() )
		{
			return response()->view($view, $function);
		}
		else
		{
			return response()->json($function);
		}
	}
	
	public function constructMenu($base) {
		$directories = [];
		foreach ( Storage::disk('leaves')->directories($base) as $sub )
		{
			$directories[$sub] = Storage::disk('leaves')->directories($sub);
		}
		return $directories;
	}
}