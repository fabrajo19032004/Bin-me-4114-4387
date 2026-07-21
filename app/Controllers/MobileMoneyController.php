<?php

namespace App\Controllers;

use App\Models\UserModel;

class MobileMoneyController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get('connecte')) {
            return redirect()->to(base_url('auth/login'));
        }

        $role = strtoupper((string) $session->get('role'));

        if ($role === 'OPERATEUR') {
            return redirect()->to(base_url('mobile-money/operator'));
        }

        return redirect()->to(base_url('mobile-money/client'));
    }

    public function login()
    {
        return view('mobile_money/login');
    }

    public function loginPost()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir tous les champs.');
        }

        $userModel = new UserModel();
        $user = $userModel->authenticate($username, $password);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Identifiants invalides.');
        }

        $this->storeSession($user);

        return redirect()->to(base_url('mobile-money'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url('mobile-money/login'));
    }

    protected function ensureOperator()
    {
        if (!session()->get('connecte')) {
            return redirect()->to(base_url('mobile-money/login'));
        }

        if (strtoupper((string) session()->get('role')) !== 'OPERATEUR') {
            return redirect()->to(base_url('mobile-money/client'));
        }

        return null;
    }

    protected function initializeDatabaseFromSql(): void
    {
        $sqlFile = ROOTPATH . 'base.sql';
        if (!is_file($sqlFile)) {
            return;
        }

        $db = db_connect();
        $tableCheck = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'")->getResultArray();
        if (!empty($tableCheck)) {
            return;
        }

        $sql = file_get_contents($sqlFile);
        if ($sql === false || trim($sql) === '') {
            return;
        }

        $sql = preg_replace('/^sqlite3\s+.+$/m', '', $sql);
        $sql = preg_replace('/^\s*--.*$/m', '', $sql);
        $sql = preg_replace('/\r\n/', "\n", $sql);
        $sql = trim($sql);

        if ($sql === '') {
            return;
        }

        $statements = preg_split('/;\s*\n/', $sql);

        foreach ($statements as $statement) {
            $statement = trim($statement);
            if ($statement === '') {
                continue;
            }

            $db->query($statement);
        }
    }

    protected function storeSession(array $user): void
    {
        session()->set([
            'connecte' => true,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'client_id' => $user['client_id'],
        ]);
    }

    protected function optional($value)
    {
        return new class($value) implements \ArrayAccess {
            private $value;

            public function __construct($value)
            {
                $this->value = $value;
            }

            public function offsetExists($offset): bool
            {
                return is_array($this->value) && array_key_exists($offset, $this->value);
            }

            public function offsetGet($offset)
            {
                if (is_array($this->value) && array_key_exists($offset, $this->value)) {
                    return $this->value[$offset];
                }

                return null;
            }

            public function offsetSet($offset, $value): void
            {
                throw new \LogicException('Cannot set value on optional wrapper.');
            }

            public function offsetUnset($offset): void
            {
                throw new \LogicException('Cannot unset value on optional wrapper.');
            }
        };
    }
}
