
export class AuthService {
  constructor() {}

  login(data) {
    const url = `api/v1/auth/login`;
    const headers = {
      'Content-Type': 'application/json',
    };
    const params = {
      username: data.username,
      password: data.password,
    };
    const options = {
      method: 'POST',
      headers: headers,
      body: JSON.stringify(params),
    };

    return fetch(url, options)
      .then((response) => response.json())
      .catch((error) => {
        console.error('Error:', error);
        throw error;
      });
  }
  // Implémentez les autres méthodes ici...

  IsLogged() {
    return localStorage.getItem('tokenUser') === 'true';
  }

  GetToken() {
    return localStorage.getItem('access-token') || '';
  }

  GetRefreshToken() {
    return localStorage.getItem('refreshtoken') || '';
  }

  // Ajoutez d'autres méthodes selon vos besoins...

  Logout() {
    alert('Your session expired');
    localStorage.clear();
    // Ici, vous pouvez rediriger l'utilisateur vers la page de connexion
  }

  // D'autres méthodes de la classe AuthService...
}




