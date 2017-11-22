pipeline {
  agent {
    docker {
      image 'php'
    }
    
  }
  stages {
    stage('Build') {
      steps {
        sh '''curl https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer | php -- --quiet
'''
        sh 'php composer.phar install'
      }
    }
    stage('Test') {
      steps {
        sh './vendor/bin/phpunit'
      }
    }
  }
}