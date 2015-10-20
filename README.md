Freetime how to project


Implementation steps
#sudo curl -LsS http://symfony.com/installer -o /usr/local/bin/symfony
#sudo chmod a+x /usr/local/bin/symfony
# go to workspace folder
#symfony new your_project
#installed some bundles: fosrest, fosuser, nelmio-api-doc
# http://symfony.com/doc/current/cookbook/security/api_key_authentication.html
# create new bundle for api
# create first controller
# activate services.yml for appBundle
# create several entities via app/console doctrine:generate:entity
# move repository into an own namespace for a better overview
# create registration action
# create constraints for user object
# create dto for user
# exception for validation
# handle validations in the controller
# ProjectController (and all calls)
# WorkTimeController (and all calls)


#todo
- security for registration and login, use of @security annotation
- unit tests
- \AppBundle\Entity\Project::$hourlyRate validate to float
