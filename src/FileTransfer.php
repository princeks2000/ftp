<?php

namespace Princeks2000\Sftp;

use phpseclib3\Net\SFTP;
use Exception;

class FileTransfer
{
    private $ftpConnection;
    private $sftpConnection;

    /**
     * Connect to an FTP server.
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param bool $passiveMode (true for passive mode, false for active mode)
     * @return bool
     * @throws Exception
     */
    public function connectFtp($host, $username, $password, $passiveMode = true)
    {
        $this->ftpConnection = ftp_connect($host);
        if (!$this->ftpConnection) {
            throw new Exception("FTP connection failed.");
        }

        $login = ftp_login($this->ftpConnection, $username, $password);
        if (!$login) {
            throw new Exception("FTP login failed.");
        }

        // Set passive or active mode
        ftp_pasv($this->ftpConnection, $passiveMode);

        return true;
    }

    /**
     * Connect to an SFTP server.
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public function connectSftp($host, $username, $password)
    {
        $this->sftpConnection = new SFTP($host);
        if (!$this->sftpConnection->login($username, $password)) {
            throw new Exception("SFTP login failed.");
        }

        return true;
    }

    /**
     * Upload a file via FTP.
     *
     * @param string $localFile
     * @param string $remoteFile
     * @return bool
     * @throws Exception
     */
    public function uploadFtp($localFile, $remoteFile)
    {
        if (!$this->ftpConnection) {
            throw new Exception("FTP connection not established.");
        }

        if (!ftp_put($this->ftpConnection, $remoteFile, $localFile, FTP_BINARY)) {
            throw new Exception("FTP upload failed.");
        }

        return true;
    }

    /**
     * Download a file via FTP.
     *
     * @param string $remoteFile
     * @param string $localFile
     * @return bool
     * @throws Exception
     */
    public function downloadFtp($remoteFile, $localFile)
    {
        if (!$this->ftpConnection) {
            throw new Exception("FTP connection not established.");
        }

        if (!ftp_get($this->ftpConnection, $localFile, $remoteFile, FTP_BINARY)) {
            throw new Exception("FTP download failed.");
        }

        return true;
    }

    /**
     * Upload a file via SFTP.
     *
     * @param string $localFile
     * @param string $remoteFile
     * @return bool
     * @throws Exception
     */
    public function uploadSftp($localFile, $remoteFile)
    {
        if (!$this->sftpConnection) {
            throw new Exception("SFTP connection not established.");
        }

        if (!$this->sftpConnection->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
            throw new Exception("SFTP upload failed.");
        }

        return true;
    }

    /**
     * Download a file via SFTP.
     *
     * @param string $remoteFile
     * @param string $localFile
     * @return bool
     * @throws Exception
     */
    public function downloadSftp($remoteFile, $localFile)
    {
        if (!$this->sftpConnection) {
            throw new Exception("SFTP connection not established.");
        }

        if (!$this->sftpConnection->get($remoteFile, $localFile)) {
            throw new Exception("SFTP download failed.");
        }

        return true;
    }

    /**
     * Close the FTP connection.
     */
    public function closeFtp()
    {
        if ($this->ftpConnection) {
            ftp_close($this->ftpConnection);
            $this->ftpConnection = null;
        }
    }

    /**
     * Close the SFTP connection.
     */
    public function closeSftp()
    {
        if ($this->sftpConnection) {
            $this->sftpConnection = null;
        }
    }
}