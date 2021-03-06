<?php

namespace F481\MSM_Frontend;

require_once __DIR__ .'/commands.php';

class MSMCommander {

    // get the values betweeen " or ' (needed for e.g. server name parsing)
    // see http://stackoverflow.com/questions/171480/regex-grabbing-values-between-quotation-marks
    private $regexGetQuotesValue = '/(["])(?:(?=(\\\?))\2.)*?\1/';
    private $regexNoServer = '/no server/i';


    private function msmCall($command)
    {
        return shell_exec('sudo msm ' .$command);
    }

    /**
     * Checks if 'msm' command is available
     *
     * @return bool Either the msm command is available or not
     */
    public function isMSMAvailable()
    {
        $output = shell_exec('msm');

        if ($output != null) {
            // if command is not found, the method returns false
            $response = preg_match('/not found/i', $output) == 1 ? false : true;
        } else {
            $response = null;
        }

        return $response;
    }

    /**
     * List servers
     *
     * @return array|null array of strings containing all servers, otherwise null if no server exists
     */
    public function getServers()
    {
        $output = $this->msmCall(constant('server_list'));
        preg_match_all($this->regexGetQuotesValue, $output, $response);

        // in response [0] are the array with the server strings
        $response = sizeof($response[0]) > 0 ? str_replace('"', '', $response[0]) : null;
        return $response;
    }

    /**
     * Creates a new Minecraft server
     *
     * @param $_name Name of the new server
     * @return string
     */
    public function createServer($_name)
    {
        $success = false;
        $output = $this->msmCall(str_replace('$name', $_name, constant('server_create')));

        if (preg_match('/already exists/i', $output) == 1 || $output == null) {
            throw new \RuntimeException(sprintf('A server with name "%s" already exists', $_name));
        }

        if (preg_match('/creating server.*done/i', $output) == 1) {
           $success = true;
        }

        return $success;
    }

    /**
     * Deletes an existing Minecraft server
     *
     * @param $_name Name of the server that should be deleted
     * @return string
     */
    public function deleteServer($_name)
    {
        return $this->msmCall(str_replace('$name', $_name, constant('server_delete')));
    }

    /**
     * Renames an existing Minecraft server
     *
     * @param $_name Actual name of the server
     * @param $_newName New name of the server
     * @return string
     */
    public function renameServer($_name, $_newName)
    {
        $command = str_replace('$name', $_name, constant('server_rename'));
        $command = str_replace('$new-name', $_newName, $command);
        return $this->msmCall($command);
    }

    /**
     * Starts a server
     *
     * @param $_server Server that should be started
     * @return string
     */
    public function serverStart($_server)
    {
        return $this->msmCall(str_replace('$server', $_server, constant('start')));
    }

    /**
     * Stops a server after warning players, or right now
     *
     * @param $_server Server that should be stopped
     * @param $now Restart right now or warn players
     * @return string
     */
    public function serverStop($_server, $now = true)
    {
        $command = str_replace('$server', $_server, constant('stop'));

        if ($now) {
            $command = $command.' now';
        }
        return $this->msmCall($command);
    }

    /**
     * Restarts a server after warning players, or right now
     *
     * @param $_server Server that should be stopped
     * @param $now Restart right now or warn players
     * @return string
     */
    public function serverRestart($_server, $now = true)
    {
        $command = str_replace('$server', $_server, constant('restart'));

        if ($now) {
            $command = $command.' now';
        }
        return $this->msmCall($command);
    }


    /**
     * Show the running/stopped status of a server
     *
     * @throws \RuntimeException if there is no server with the given name
     * @param $_server Server to get status
     * @return boolean|null
     */
    public function isRunning($_server)
    {
        $output = $this->msmCall(str_replace('$server', $_server, constant('status')));

        if (preg_match($this->regexNoServer, $output) == 1 || $output == null) {
            throw new \RuntimeException(sprintf('There is no server with the name "%s"', $_server));
        }

        if (stripos($output, 'running') !== false) {
            $isRunning = true;
        } else if (stripos($output, 'stopped') !== false) {
            $isRunning = false;
        }

        return $isRunning;
    }

    /**
     * List a servers connected players
     *
     * @throws \RuntimeException if there is no server with the given name
     * @param $_server
     * @return array|null array of string containing the players, otherwise null if no player is connected
     */
    public function getPlayers($_server)
    {
        $output = $this->msmCall(str_replace('$server', $_server, constant('connected')));

        if (preg_match($this->regexNoServer, $output) == 1 || $output == null) {
            throw new \RuntimeException(sprintf('There is no server with the name "%s"', $_server));
        }

        if (stripos($output, 'no') === false) {
            foreach ($players = explode(',', $output) as $player) {
                trim($player);
            }
        } else {
            $players = null;
        }

        return $players;
    }
}