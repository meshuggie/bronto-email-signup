# bronto-email-signup
Built from the [WordPress Plugin Boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate) by Devin Vinson.

The signup will broadcast a custom event "brontoSignup" when submitted. You will find the result in e.detail.response, which will either state "error" or "success". For example:
```javascript
$('body').on('brontoSignup', function( e ) {
  console.log(e.detail.response);
});
```
