# Photos uploading

For [uploading](https://github.com/500px/api-documentation/blob/master/endpoints/upload/POST_upload.md) photos is used different api, than for other edit/update calls.

This extension brings you special method which could handle all this stuff.

## Uploading photos

For successful upload, user have to be authenticated and your app must have *write* permission. Upload is simple done with this call:

```php
class YourAppSomePresenter extends BasePresenter
{
	/**
	 * @var \IPub\FiveHundredPixel\Client
	 */
	protected $fiveHundredPixel;

	public function actionUpload()
	{
		try {
			$photoDetails = $this->fiveHundredPixel->uploadPhoto('full/absolute/path/to/your/image.jpg', [
				'name' => 'Here could be some image title',
				'description' => 'And here you can place some description'
			]);

		} catch (\IPub\OAuth\ApiException $ex) {
			// something went wrong in API call

		} catch (\IPub\FiveHundredPixel\Exceptions\FileUploadFailedException $ex) {
			// Photo could not be uploaded
		}
	}
}
```

If upload is successful an [photo details array](https://github.com/500px/api-documentation/blob/master/endpoints/photo/POST_photos.md#example) is returned, in other case an exception will be thrown.

All additional params are optional, so you can omit the second argument of the *uploadPhoto* method, but if you want, you can define this parameters:

* **name** - The title of the photo
* **description** - A description of the photo. May contain some limited HTML
* **category** - A numerical ID for the Category of the photo
* **tags** - A space-seperated list of tags to apply to the photo
* and [other parameters](https://github.com/500px/api-documentation/blob/master/endpoints/photo/POST_photos.md#parameters)
