package 
{
	import flash.events.Event;
	import flash.display.Loader;
	import flash.net.URLRequest;

	public class MyLoader
	{
		private var obj:Object = new Object();
		public var func:Function;

		public function MyLoader()
		{
		}

		public function get para():Object
		{
			return obj;
		}

		public function loadImg(url:String):void
		{
			var imgLoader:Loader = new Loader();
			imgLoader.load(new URLRequest(url));
			imgLoader.contentLoaderInfo.addEventListener(Event.COMPLETE,onImgLoaded);
		}

		private function onImgLoaded(e:Event)
		{
			if(func != null) func(this,e);
		}
	}

}