### I. Tổng quan* OAuth 2.0 là một authorization framework cho phép các ứng dụng bên thứ ba có quyền truy cập hạn chế đến một dịch vụ HTTP* OAuth khắc phục những hạn chế của phương pháp client-server truyền thống bằng việc giới thiệu authorization layer và tách biệt vai trò của client ra khỏi resource owner. Trong OAuth client truy cập các tài nguyên đã được bảo vệ dưới sự giám sát của resource owner, tài nguyên sẽ được lưu trữ tại resource server. Thay vì sử dụng thông tin của resource owner, client sẽ sử dụng access token (là một chuỗi mã hóa các thuộc tính truy cập). Access token được cung cấp cho client thông qua authorization server cùng với sự chấp thuận của resource owner. Client sau đó có thể sử dụng access token để truy cập các tài nguyên đã được bảo vệ trên resource server.### II. Các tác nhân trong Oauth2* resource owner : là một thực thể có khả năng cấp quyền truy cập cho các tài nguyên đã được bảo vệ, khi resource owner là một người cụ thể, chúng ta thường gọi là end-user.* resource server : là nơi lưu trữ các tài nguyên đã được bảo vệ, có chức năng tiếp nhận và xử lý các yêu cầu truy cập sử dụng access token.* client : là các ứng dụng bên thứ ba, các ứng dụng này sẽ gửi các yêu cầu truy cập protected resource dưới sự chấp thuận của resource owner.* authorization server : server này có chức năng cung cấp access token cho client sau khi client đã xác thực với resource owner và nhận được sự ủy quyền từ phía resource owner.### III. Các phương pháp get token#### 1. Issuing Access Tokens(Authorization Grant)> Grant này khá phổ biến và được sử dụng rộng trong các ứng dụng web hiện đại. Grant này bao gồm hai bước cơ bản:      **Obtaining authorization code**      Trong bước này client sẽ được chuyển tiếp đến authorization server với các parameter sau trong request:      * respone_type: với giá trị là code   * client_id: định danh của client   * redirect_uri: nơi mà resource owner sẽ được authorization server chuyển tiếp về phía client sau khi quá trình giao tiếp giữu authorization server và resource owner hoàn tất. Parameter này không bắt buộc phải có trong request.   * scope: danh sách các quyền (permissions)   * state: thông thường sẽ là CSRF token.      Tất cả các parameter trên sẽ được kiểm chứng bởi authorization server. Nếu như resource owner chấp thuận yêu cầu, resource owner sẽ được chuyển tiếp từ authorization server về phía client với những thông tin sau:      * code: authorization code   * state: giá trị của state trong request nói trên, giá trị này sẽ được sử dụng để kiểm tra authorization code được trả về cho client thực hiện request nói trên.      **Acquiring Access Token**      Sau khi đã nhận được authorization code, client sẽ tạo một POST request đến authorization server với các parameter sau:      * respone_type: với giá trị là authorization_code   * client_id: định danh của client   * client_secret: client secret   * redirect_uri: nơi mà resource owner sẽ được authorization server chuyển tiếp về phía client sau khi quá trình giao tiếp giữu authorization server và resource owner hoàn tất. Parameter này không bắt buộc phải có trong request.   * code: authorization code thu được từ bước 1.      Authorization server sẽ trả về các thông tin sau:      * token_type: kiểu của access token (ví dụ: Bearer)   * expires_in: thời gian sống của access token (TTL / Time-To-Live)   * access_token: giá trị của access token   * refresh_token: giá trị của refresh_token, token này được sử dụng để tạo access token mới khi access token cũ đã hết hạn (expired)   #### 2. Password Grant Tokens>Pasword Grant được sử dụng khi mối quan hệ giữa client và resource owner là đáng tin cậy và gần gũi. Trong loại grant này, client sẽ yêu cầu thông tin từ phía resource owner bao gồm username và password; client sau đó sẽ gửi request lên authorization server với các parameter sau:* grant_type: với giá trị là password* client_id: định danh của client* client_secret: giá trị secret của client* scope: danh sách các quyền (permissions)* username: username của resource owner* password: password của resource ownerAuthorization server sẽ trả về những thông tin sau nếu như các parameter trên là chính xác:* token_type: kiểu của access token (ví dụ: Bearer)* expires_in: thời gian sống của access token (TTL / Time-To-Live)* access_token: giá trị của access token* refresh_token: giá trị của refresh_token, token này được sử dụng để tạo access token mới khi access token cũ đã hết hạn (expired)#### 3. Implicit Grant Tokens>Implicit Grant khá giống so với Authorization Grant tuy nhiên có hai điểm khác biệt chính là: giá trị client_secret sẽ không được sử dụng, và authorization code sẽ không được tạo ra thay vào đó sẽ là giá trị của access tokenCụ thể, client sẽ chuyển tiếp resource owner đến authorization server với các parameter sau:* respone_type: với giá trị là token* client_id: định danh của client* redirect_uri: nơi mà resource owner sẽ được authorization server chuyển tiếp về phía client sau khi quá trình giao tiếp giữu authorization server và resource owner hoàn tất. Parameter này không bắt buộc phải có trong request.* scope: danh sách các quyền (permissions)* state: thông thường sẽ là CSRF token.Tất cả các parameter trên sẽ được kiểm chứng bởi authorization server. Nếu như resource owner chấp thuận yêu cầu, resource owner sẽ được chuyển tiếp từ authorization server về phía client với những thông tin sau:* token_type: kiểu của access token (ví dụ: Bearer)* expires_in: thời gian sống của access token (TTL / Time-To-Live)* access_token: giá trị của access token* state: giá trị của state trong request nói trên, giá trị này sẽ được sử dụng để kiểm tra authorization code được trả về cho client thực hiện request nói trên.#### 4. Client Credentials Grant Tokens>Đây là dạng grant đơn giản nhất của OAuth 2.0 được sử dụng khi việc truy cập protected resource không yêu cầu permissions từ resource ownerClient sẽ gửi request đến authorization server với các parameter sau:* grant_type: với giá trị là client_credentials* client_id: định danh của client* client_secret: giá trị secret của client* scope: danh sách các quyền (permissions)Authorization server sẽ trả về những thôn tin sau nếu như các parameter trên là chính xác:* token_type: kiểu của access token (ví dụ: Bearer)* expires_in: thời gian sống của access token (TTL / Time-To-Live)* access_token: giá trị của access token### IV. Cài đặt và cấu hìnhCài đặt packge qua composer    composer require laravel/passportThêm service provider của Laravel Passport trong file `config/app.php`    Laravel\Passport\PassportServiceProvider::class,Laravel Passport cung cấp sẵn một số migration class để tạo các bảng cần thiết để lưu trữ authorization codes, access tokens, refresh tokens, personal access tokens, thông tin về clients    php artisan migrateTạo encryption keys (được dùng khi tạo access tokens) và tạo các client liên quan đến Personal Access Grant và Password Grant"    php artisan passport:installThêm một trait - `Laravel\Passport\HasApiTokens` cho authentication model - mặc định sẽ là `App\User`. `HasApiTokens` trait cung cấp một số phương thức trợ giúp trong việc lấy thông tin liên quan đến token của user, kiểm tra scope (permission) hoặc tạo mới personal access token.```<?php      namespace App;      use Laravel\Passport\HasApiTokens;   use Illuminate\Notifications\Notifiable;   use Illuminate\Foundation\Auth\User as Authenticatable;      class User extends Authenticatable   {       use HasApiTokens, Notifiable;   }```Laravel Passport cũng cung cấp một dòng lệnh khá đơn giản để đăng ký một số API endpoint liên quan đến OAuth. Chúng ta có thể thực hiện việc trên bằng cách thêm câu lệnh `Passport::routes()` vào trên trong phương thức `boot()` của `app/Providers/AuthServiceProvider` cung cấp sẵn bởi Laravel```<?php      namespace App\Providers;      use Laravel\Passport\Passport;   use Illuminate\Support\Facades\Gate;   use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;      class AuthServiceProvider extends ServiceProvider   {       /**        * The policy mappings for the application.        *        * @var array        */       protected $policies = [           'App\Model' => 'App\Policies\ModelPolicy',       ];          /**        * Register any authentication / authorization services.        *        * @return void        */       public function boot()       {           $this->registerPolicies();              Passport::routes();       }   }```Bước cuối cùng là chuyển api `driver` từ token sang `passport` trong file `config/auth.php````'guards' => [    'web' => [        'driver' => 'session',        'provider' => 'users',    ],    'api' => [        'driver' => 'passport',        'provider' => 'users',    ],],```Tiếp theo chúng ta sẽ cấu hình thời gian sống cho refresh tokens và access tokens, việc này có thể thực hiện trong AuthServiceProvider. Mặc định những token này sẽ có thời gian sống khá dài sau 1 năm và access token sẽ không cần phải được refreshing.Có thể sử dụng các method tokensExpireIn,  refreshTokensExpireIn, and personalAccessTokensExpireIn để thiết lập lại thời gian mặc định này trong AuthServiceProvider.```/** * Register any authentication / authorization services. * * @return void */public function boot(){    $this->registerPolicies();    Passport::routes();    Passport::tokensExpireIn(now()->addDays(15));    Passport::refreshTokensExpireIn(now()->addDays(30));    Passport::personalAccessTokensExpireIn(now()->addMonths(6));}```### V. Các phương pháp get token trong Laravel Passport### 1. Issuing Access TokensTrong Laravel Passport, việc sử dụng loại grant này được thực hiện qua một số bước như sau:* Tạo mới client: Việc tạo mới client trong Laravel Passport được thực hiện quan một Artisan console command. Sau khí quá trình thực hiện command kết thúc, một client mới sẽ được tạo mới một số attribute như: name, secret và redirect.```php artisan passport:client```> Vì người dùng không thể sử dụng câu lệnh trên. Laravel passport cung cấp sẵn các api json để quản lý client này.1) Lấy danh sách các client```axios.get('/oauth/clients')    .then(response => {        console.log(response.data);    });```2) Tạo clientĐầu vào của request này là name và redirect là nơi chuyển hướng sau khi đc phê duyệt authorization.Sau khi client được tạo ra. Nó sẽ được cấp 1 client ID và 1 client secret, các giá trị này sẽ được sử dụng để yêu cầu lấy access token```const data = {    name: 'Client Name',    redirect: 'http://example.com/callback'};axios.post('/oauth/clients', data)    .then(response => {        console.log(response.data);    })    .catch (response => {        // List errors on response...    });```3) Cập nhật clientĐể cập nhật cần thêm tham số clientID vào url put và dữ liệu đầu vào là name hoặc redirect```const data = {    name: 'New Client Name',    redirect: 'http://example.com/callback'};axios.put('/oauth/clients/' + clientId, data)    .then(response => {        console.log(response.data);    })    .catch (response => {        // List errors on response...    });```4) Xóa clientĐể xóa client sử dụng method delete với tham số url là clientID```axios.delete('/oauth/clients/' + clientId)    .then(response => {        //    });```* Request authorzation code: Sau khi client đã được tạo, client ID và client secret sẽ được sử dụng để request authorization code và access token. Việc request authorization code sẽ được thực hiện bằng cách redirect người dùng đến /oauth/authorize route cùng với các parameter: response_type (code), client_id, redirect_uri và scope. Nếu người dùng chấp thuận request nói trên, người dùng sẽ được redirect từ authroization server về redirect_uri đã cung cấp của client với các giá trị: code và state.```Route::get('/redirect', function () {    $query = http_build_query([        'client_id' => 'client-id',        'redirect_uri' => 'http://example.com/callback',        'response_type' => 'code',        'scope' => '',    ]);    return redirect('http://your-app.com/oauth/authorize?'.$query);});```* Converting Authorization Codes To Access Tokens: Sau khi đã có được authorization code, client sẽ thực hiện một POST request đến /oauth/token route với authorization code đã có ở bước trên để lấy về access token.```Route::get('/callback', function (Request $request) {    $http = new GuzzleHttp\Client;    $response = $http->post('http://your-app.com/oauth/token', [        'form_params' => [            'grant_type' => 'authorization_code',            'client_id' => 'client-id',            'client_secret' => 'client-secret',            'redirect_uri' => 'http://example.com/callback',            'code' => $request->code,        ],    ]);    return json_decode((string) $response->getBody(), true);});```Response sẽ là 1 json bao gồm access_token,  refresh_token, and expires_in. expires_in là thời gian sống của token tính bằng giây* Refreshing Tokens: Trong trường hợp access token có thời gian sống ngắn, chúng ta có thể sử dụng refresh token để tạo mới một access token. Với Laravel Passport, việc trên có thể thực hiện bằng việc tạo một POST request đến route /oauth/token với các parameters: grant_type (refresh_token), client_id, client_secret, và scope. Tương tự như khi lấy token ban đầu.```$http = new GuzzleHttp\Client;$response = $http->post('http://your-app.com/oauth/token', [    'form_params' => [        'grant_type' => 'refresh_token',        'refresh_token' => 'the-refresh-token',        'client_id' => 'client-id',        'client_secret' => 'client-secret',        'scope' => '',    ],]);return json_decode((string) $response->getBody(), true);```Response sẽ vẫn là 1 json bao gồm access_token,  refresh_token, and expires_in. expires_in là thời gian sống của token tính bằng giây### 2. Password Grant TokensTương tự như Authorization Code Grant, chúng ta cần tạo client khi sử dụng loại grant này. Việc tạo mới khá tương tự, tuy nhiên chúng ta cần thêm --password flag cho client command```php artisan passport:client --password```Việc request access token cũng được thực hiện bằng việc gửi POST request đến /oauth/token route với các parameters sau: grant_type (password), client_id, client_secret, username, password và scope. Nếu request được thực hiện thành công, kết quả trả về sẽ bao gồm: token_type, expires_in (thời gian sống cho access token), access_token và refresh_token.```$http = new GuzzleHttp\Client;$response = $http->post('http://your-app.com/oauth/token', [    'form_params' => [        'grant_type' => 'password',        'client_id' => 'client-id',        'client_secret' => 'client-secret',        'username' => 'taylor@laravel.com',        'password' => 'my-password',        'scope' => '',    ],]);return json_decode((string) $response->getBody(), true);```Khi sử dụng password grant hoặc client credentials grant, bạn có thể muốn authorize the token cho tất cả các scopes được ứng dụng của bạn hỗ trợ.Bạn có thể làm điều này bằng cách sử dụng scope `*`. Nếu bạn sử dụng scope `*`, phương thức `can` trên đối tượng token sẽ luôn trả về đúng. Scope này chỉ có thể được gán cho mã thông báo được cấp bằng `password` hoặc `client_credentials` grant.```$response = $http->post('http://your-app.com/oauth/token', [    'form_params' => [        'grant_type' => 'password',        'client_id' => 'client-id',        'client_secret' => 'client-secret',        'username' => 'taylor@laravel.com',        'password' => 'my-password',        'scope' => '*',    ],]);```>Khi sử dụng xác thực bằng pasword grant, mặc định passport sử dụng email trong model làm username. Vì vậy nếu muốn thay đổi lại có thể sử dụng method findForPassport trong model User để định nghĩa lại```<?phpnamespace App;use Laravel\Passport\HasApiTokens;use Illuminate\Notifications\Notifiable;use Illuminate\Foundation\Auth\User as Authenticatable;class User extends Authenticatable{    use HasApiTokens, Notifiable;    /**     * Find the user instance for the given username.     *     * @param  string  $username     * @return \App\User     */    public function findForPassport($username)    {        return $this->where('username', $username)->first();    }}```Tương tự đối với mật khẩu. Passport sử dụng password trong model nếu trong csdl không có hoặc muốn thay đổi lại logic có thể sử dụng method validateForPassportPasswordGrant để định nghĩa lại:```<?phpnamespace App;use Laravel\Passport\HasApiTokens;use Illuminate\Support\Facades\Hash;use Illuminate\Notifications\Notifiable;use Illuminate\Foundation\Auth\User as Authenticatable;class User extends Authenticatable{    use HasApiTokens, Notifiable;    /**    * Validate the password of the user for the Passport password grant.    *    * @param  string $password    * @return bool    */    public function validateForPassportPasswordGrant($password)    {        return Hash::check($password, $this->password);    }}```### 3. Implicit Grant TokensCấp quyền ngầm tương tự như authorization code grant; tuy nhiên, mã thông báo được trả lại cho client mà không trao đổi authorization code.Grant này được sử dụng phổ biến nhất cho các ứng dụng JavaScript hoặc di động nơi thông tin đăng nhập của khách hàng không thể được lưu trữ an toàn. Để kích hoạt grant, gọi phương thức `enableImplicitGrant` trong `AuthServiceProvider` của bạn.```/** * Register any authentication / authorization services. * * @return void */public function boot(){    $this->registerPolicies();    Passport::routes();    Passport::enableImplicitGrant();}```Sau khi được kích hoạt sử dụng clientID để yêu cầu lấy access token thông qua route `/oauth/authorize````Route::get('/redirect', function () {    $query = http_build_query([        'client_id' => 'client-id',        'redirect_uri' => 'http://example.com/callback',        'response_type' => 'token',        'scope' => '',    ]);    return redirect('http://your-app.com/oauth/authorize?'.$query);});```### 4. Client Credentials Grant TokensLoại ủy quyền này phù hợp với các xác thực từ máy chủ đến máy chủ, ví dụ bạn sử dụng ủy quyền này trong các job được lập lịch, thực hiện các tác vụ bảo trì thông qua API. Để lấy được access token, chỉ cần gửi một request đến oauth/token:Đầu tiên cần tạo client sử dụng command:```php artisan passport:client --client```Tiếp theo để sử dụng grant này thêm middleware `CheckClientCredentials` vào property `$routeMiddleware` trong file `app/Http/Kernel.php````use Laravel\Passport\Http\Middleware\CheckClientCredentials;protected $routeMiddleware = [    'client' => CheckClientCredentials::class,];```Thêm `middleware('client')` vào nhưng route nào sử dụng grant nàyĐể hạn chế truy cập vào các scope thêm các scope vào bên trong middleware, nếu có nhiều cần cách nhau bằng dấu phẩy.```middleware('client:check-status,your-scope')```Để lấy được token từ grant này cần gửi request POST đến `oauth/token````$guzzle = new GuzzleHttp\Client;$response = $guzzle->post('http://your-app.com/oauth/token', [    'form_params' => [        'grant_type' => 'client_credentials',        'client_id' => 'client-id',        'client_secret' => 'client-secret',        'scope' => 'your-scope',    ],]);return json_decode((string) $response->getBody(), true)['access_token'];```## Tóm tắt lại*   Issuing Access Tokens(Authentication Access Token)Loại này cần xác thực thông qua authentication để lấy authorization code=> Tạo client=> Gửi request GET đến `oauth/authorize` với client_id,'response_type' => 'code' lấy authorization code=> Gửi client_id + client_serect + code(authorization code) + 'grant_type' => 'authorization_code', lấy token*   Password Grant TokensLoại này không cần xác thực qua authentication lấy authorization code mà thông qua thông tin đăng nhập của user=> Tạo client (loại password grant)=> Tạo 1 request POST đến `oauth/token` với các tham số client_id, client_secret, username/email, password(tùy theo cài đặt mặc định là email/password) + 'grant_type' => 'password'*   Implicit Grant TokensLoại này cần xác thực qua authentication nhưng không cần authorization code> Để sử dụng loại này cần khai báo method enableImplicitGrant trong AuthServiceProvider=> Tạo client bình thường=> Gửi request GET đến /oauth/authorize với tham số là clientID*   Client Credentials Grant TokensLoại này cũng không cần xác thực qua authentication. Để lấy được access token, chỉ cần gửi một request POST đến oauth/token=> Tạo client => Gửi request POST tham số 'grant_type' => 'client_credentials', client_id, client_secret