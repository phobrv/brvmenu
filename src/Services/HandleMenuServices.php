<?php
namespace Phobrv\BrvMenu\Services;
use Phobrv\BrvConfigs\Services\ConfigLangService;
use Phobrv\BrvCore\Repositories\PostRepository;
use Phobrv\BrvCore\Repositories\TermRepository;

class HandleMenuServices {
	protected $termRepository;
	protected $configLangService;
	protected $postRepository;
	protected $langMain;
	public function __construct(
		ConfigLangService $configLangService,
		PostRepository $postRepository,
		TermRepository $termRepository
	) {
		$this->configLangService = $configLangService;
		$this->termRepository = $termRepository;
		$this->postRepository = $postRepository;
		$this->langMain = $configLangService->getMainLang();
	}
	public function getMenus($configs, $menu_key, $disablePrivateMenu = NULL) {
		if (!isset($configs[$menu_key])) {
			return "";
		}
		$posts = $this->termRepository->find($configs[$menu_key])->posts()->where('lang', config('app.locale'))->orderBy('order')->with('postMetas')->get();
		return $this->handleMenuItem($posts, ['disablePrivateMenu' => $disablePrivateMenu]);
	}
	public function handleMenuItem($posts, $option = []) {
		$menus = array();
		$curRequest = str_replace("/", "", $_SERVER['REQUEST_URI']);
		$langArray = $this->configLangService->getArrayLangConfig();
		foreach ($posts as $p) {
			if (isset($option['disablePrivateMenu']) || $p->status == 1) {

				$p->active = $this->handleMenuAcitve($p, $curRequest);
				$p->url = $this->handleUrlMenu($p);
				$icon = $p->postMetas->where('key', 'icon')->first();
				$p->icon = isset($icon->value) ? $icon->value : '';
				if (isset($option['langButton'])) {
					$p->langButtons = $this->configLangService->genLangButton($p->id, $langArray);
				}

				if ($p->parent == 0) {
					if (isset($option['disablePrivateMenu'])) {
						$childs = $this->postRepository->where('parent', $p->id)->where('status', '1')->orderBy('order')->get();
					} else {
						$childs = $this->postRepository->where('parent', $p->id)->orderBy('order')->get();
					}

					if ($childs) {
						if ($childs->where('slug', $curRequest)->first()) {
							$p->active = "active";
						}
						for ($i = 0; $i < count($childs); $i++) {
							$childs[$i]->url = $this->handleUrlMenu($childs[$i]);
							if (isset($option['langButton'])) {
								$childs[$i]->langButtons = $this->configLangService->genLangButton($childs[$i]->id, $langArray);
							}

						}
						$p->childs = $childs;
					}
					array_push($menus, $p);
				}
			}

		}
		return $menus;
	}

	public function handleMenuAcitve($p, $curRequest) {
		if ($p->subtype == "home" && $curRequest == "") {
			$active = "active";
		} elseif ($curRequest == $p->slug) {
			$active = "active";
		} else {
			$active = "";
		}

		return $active;
	}
	public function handleUrlMenu($p) {
		$url = "";
		if ($p->subtype == "home" && $p->lang == config('option.langMain')) {
			$url = route('home');
		} elseif ($p->subtype == "link") {
			$url = $p->excerpt;
		} else {
			$url = route('level1', ['slug' => $p->slug]);
		}
		return $url;
	}
}