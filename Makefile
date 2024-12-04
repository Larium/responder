IMAGE_NAME = responder
IMAGE_TAG = latest
CONTAINER_NAME = responder
TESTS_DIR = tests

.PHONY: build run clean

# Build the Docker image
build:
	docker build -f .docker/Dockerfile -t $(IMAGE_NAME):$(IMAGE_TAG) .

# Run PHPUnit tests in the Docker container
run:
	docker run --name $(CONTAINER_NAME) -v $(shell pwd):/opt/php $(IMAGE_NAME):$(IMAGE_TAG) sh -c './vendor/bin/phpunit $(TESTS_DIR)/'

composer-update:
	docker run --name $(CONTAINER_NAME) -v $(shell pwd):/opt/php $(IMAGE_NAME):$(IMAGE_TAG) sh -c 'composer update'

# Remove the Docker container (if it exists)
clean:
	docker rm -f $(CONTAINER_NAME) || true
