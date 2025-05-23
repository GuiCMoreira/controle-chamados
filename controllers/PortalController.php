<?php
require_once 'models/Cliente.php';

class PortalController {
    private $db;
    private $cliente;

    public function __construct($db) {
        $this->db = $db;
        $this->cliente = new Cliente($db);
    }

    public function acessar() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?route=clientes');
            exit;
        }

        $this->cliente->id = $_GET['id'];
        $stmt = $this->cliente->readOne();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            header('Location: index.php?route=clientes');
            exit;
        }

        // Gerar página HTML temporária
        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Acessando Portal TOTVS</title>
            <script>
                window.onload = function() {
                    // Abrir o portal em uma nova janela anônima
                    const portalUrl = 'https://totvs.fluigidentity.com/ui/login?forward=%2Flaunchpad%2FlaunchApp%2F0w68h87llm8e2rp41463690860303%2Fzf0y84vo717g8hjx%3FRelayState%3Dhttps%253A%252F%252Fcentraldeatendimento.totvs.com%252Fhc%252Fpt-br%26brand_id%3D1509248%26SAMLRequest%3DfZFPT8JAEMXvfIrN3rft1hbKhpY0EJMmaAyoB29rOw2N%252B6fubBH99IYqCR7w%250AOvN%252BeW%252FeLJZHrcgBHHbW5JQHEV0WkwVKrXpRDn5vtvA%252BAHpy1MqgGBc5HZwR%250AVmKHwkgNKHwtduXdRsRBJHpnva2tohfI%252F4REBOc7ayip1jk9AY61bcqBv6Ys%250ATZqYJWl2w%252BaZnLM0y9JZlk3nWcspqRAHqAx6aXxO4yhOWDRjfPrIpyKeiTh5%250AoeT5fF0cRJSUZ7OVNThocDtwh66Gp%252B0mp3vvexRh6K0%252FIA69dR6CLzAN4FtQ%250AWx3KugbE8BSRFmNNYozgimvIIrxU%252FTZ7LzVU6weruvqTlErZj5UD6SGn3g1A%250Aya11WvrrrfGAj5OuYe0oFaBlp8qmcYBIw%252BLH9e8Li8k3%250A';
                    
                    // Criar um link temporário
                    const link = document.createElement('a');
                    link.href = portalUrl;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    
                    // Adicionar atributos para forçar modo anônimo
                    link.setAttribute('data-cy', 'anonymous-mode');
                    link.setAttribute('data-private-browsing', 'true');
                    
                    // Simular clique com tecla Ctrl pressionada (abre em nova guia)
                    const clickEvent = new MouseEvent('click', {
                        view: window,
                        bubbles: true,
                        cancelable: true,
                        ctrlKey: true
                    });
                    
                    // Adicionar o link ao documento e clicar
                    document.body.appendChild(link);
                    link.dispatchEvent(clickEvent);
                    document.body.removeChild(link);

                    // Aguardar o carregamento da página e preencher o formulário
                    setTimeout(function() {
                        const email = '{$cliente['email_portal']}';
                        const senha = '{$cliente['senha_portal']}';
                        
                        // Tentar preencher o formulário
                        try {
                            // Aguardar o carregamento do formulário
                            const checkForm = setInterval(function() {
                                const emailInput = document.querySelector('input[type="email"]');
                                const senhaInput = document.querySelector('input[type="password"]');
                                
                                if (emailInput && senhaInput) {
                                    clearInterval(checkForm);
                                    
                                    // Preencher os campos
                                    emailInput.value = email;
                                    senhaInput.value = senha;
                                    
                                    // Disparar eventos para garantir que o preenchimento seja reconhecido
                                    emailInput.dispatchEvent(new Event('input', { bubbles: true }));
                                    senhaInput.dispatchEvent(new Event('input', { bubbles: true }));
                                    
                                    // Tentar clicar no botão de login
                                    const loginButton = document.querySelector('button[type="submit"]');
                                    if (loginButton) {
                                        loginButton.click();
                                    }
                                }
                            }, 500);
                        } catch (error) {
                            console.error('Erro ao preencher formulário:', error);
                        }
                    }, 2000);
                    
                    // Fechar esta janela após um breve delay
                    setTimeout(function() {
                        window.close();
                    }, 1000);
                };
            </script>
        </head>
        <body>
            <h1>Acessando o Portal TOTVS em modo anônimo...</h1>
            <p>Uma nova guia será aberta. Se o preenchimento automático não funcionar, você pode copiar as credenciais do modal.</p>
        </body>
        </html>
        HTML;

        echo $html;
        exit;
    }
} 