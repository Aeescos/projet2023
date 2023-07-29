import { AuthService } from '../service/AuthService.js';

export class Interceptor {

    constructor() {
        this.authService = new AuthService();
    }

    httpErrorInterceptor(xhr, settings) {
        const token = this.authService.GetToken();
        
        if (token) {
            settings.headers = settings.headers || {};
            settings.headers.Authorization = `Bearer ${token}`;
        }
    
        if (authService.IsLogged()) {
            return $.ajax(settings).fail(function(xhr, textStatus, errorThrown) {
                if (xhr.status === 401) {
                    refreshToken(authService).done(function(newToken) {
                        localStorage.setItem('token', newToken);
                        localStorage.setItem('refreshtoken', newToken);
                        settings.headers.Authorization = `Bearer ${authService.GetRefreshToken()}`;
                        $.ajax(settings);
                    });
                }
            });
        } else {
            return $.ajax(settings);
        }
    }

    refreshToken(authService) {
        return $.ajax({
            url: 'http://localhost:3684/refreshToken',
            type: 'POST',
            data: {},
            xhrFields: {
                withCredentials: true
            }
        });
    }
}

$.ajaxSetup({
    beforeSend: httpErrorInterceptor
});
