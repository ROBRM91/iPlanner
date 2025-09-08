<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conexión a Base de Datos - iPlanner</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #2c3e50, #1a2530);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
        }
        
        .header {
            background: #3498db;
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .form-container {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #2c3e50;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .form-group input:focus {
            border-color: #3498db;
            outline: none;
        }
        
        .btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #219653;
        }
        
        .error-message {
            background: #fadbd8;
            color: #e74c3c;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        
        .success-message {
            background: #d4efdf;
            color: #27ae60;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        
        .info {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .app-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .app-icon {
            font-size: 32px;
            color: #3498db;
            margin-right: 10px;
        }
        
        .app-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="app-info">
            <i class="fas fa-wallet app-icon"></i>
            <span class="app-name">iPlanner</span>
        </div>
        
        <div class="header">
            <h1>Conectar Base de Datos</h1>
            <p>Ingresa los datos de conexión a tu base de datos MySQL</p>
        </div>
        
        <div class="form-container">
            <div id="error-message" class="error-message">
                <i class="fas fa-exclamation-circle"></i> 
                <span id="error-text"></span>
            </div>
            
            <div id="success-message" class="success-message">
                <i class="fas fa-check-circle"></i> 
                <span id="success-text"></span>
            </div>
            
            <form id="connection-form">
                <div class="form-group">
                    <label for="server_name">Servidor:</label>
                    <input type="text" id="server_name" name="server_name" required 
                           placeholder="Ej: localhost o dirección IP">
                </div>
                
                <div class="form-group">
                    <label for="server_port">Puerto:</label>
                    <input type="number" id="server_port" name="server_port" required 
                           value="3306" placeholder="Ej: 3306">
                </div>

                <div class="form-group">
                    <label for="user_name">Usuario:</label>
                    <input type="text" id="user_name" name="user_name" required 
                           placeholder="Usuario de la base de datos">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Contraseña (si existe)">
                </div>

                <div class="form-group">
                    <label for="db_name">Base de Datos:</label>
                    <input type="text" id="db_name" name="db_name" required 
                           placeholder="Nombre de la base de datos">
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-plug"></i> CONECTAR
                </button>
            </form>
            
            <div class="info">
                <p>Si no tienes una base de datos, crea una llamada "budgetmaster" en tu servidor MySQL</p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('connection-form');
            const errorMessage = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            const successMessage = document.getElementById('success-message');
            const successText = document.getElementById('success-text');
            
            // Verificar si hay parámetros de error en la URL
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            
            if (error) {
                errorText.textContent = decodeURIComponent(error);
                errorMessage.style.display = 'block';
            }
            
            // Manejar envío del formulario
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Obtener valores del formulario
                const formData = {
                    server_name: document.getElementById('server_name').value,
                    server_port: document.getElementById('server_port').value,
                    user_name: document.getElementById('user_name').value,
                    password: document.getElementById('password').value,
                    db_name: document.getElementById('db_name').value
                };
                
                // Validaciones básicas
                if (!formData.server_name || !formData.user_name || !formData.db_name) {
                    showError('Por favor, completa todos los campos obligatorios');
                    return;
                }
                
                // Mostrar estado de conexión
                showSuccess('Conectando...');
                
                // Enviar datos al servidor usando fetch
                fetch('connect.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(formData)
                })
                .then(response => {
                    if (response.redirected) {
                        // Si hay redirección, seguirla
                        window.location.href = response.url;
                    } else {
                        return response.text();
                    }
                })
                .then(data => {
                    if (data) {
                        // Si hay datos en la respuesta, verificar si es un error
                        if (data.includes('Error') || data.includes('error')) {
                            showError(data);
                        } else {
                            showSuccess(data);
                            // Redirigir después de 2 segundos
                            setTimeout(() => {
                                window.location.href = 'iplanner-catalogos.html';
                            }, 2000);
                        }
                    }
                })
                .catch(error => {
                    showError('Error de conexión: ' + error.message);
                });
            });
            
            function showError(message) {
                errorText.textContent = message;
                errorMessage.style.display = 'block';
                successMessage.style.display = 'none';
            }
            
            function showSuccess(message) {
                successText.textContent = message;
                successMessage.style.display = 'block';
                errorMessage.style.display = 'none';
            }
        });
    </script>
</body>
</html>