# bronto-email-signup
The signup will broadcast a custom event "brontoSignup" when submitted. You will find the result in e.detail.response, which will either state "error" or "success". For example:
```javascript
$('body').on('brontoSignup', function( e ) {
  console.log(e.detail.response);
});
```
