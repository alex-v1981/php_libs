    private static function rmdir( $dir )
    {
        if ( !file_exists( $dir ) )
            return;

        foreach(scandir($dir) as $file)
        {
            if ('.' === $file || '..' === $file)
                continue;

            if ( is_dir("$dir/$file") )
                self::rmdir("$dir/$file");
            else
                unlink("$dir/$file");
        }

        rmdir( $dir );
    }
