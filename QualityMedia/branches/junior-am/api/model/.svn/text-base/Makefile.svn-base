AMPERSAND=~/.cabal/bin/ampersand

all: qualitymedia.pdf 
test: qualitymedia-test.pdf 

qualitymedia.pdf: qualitymedia.adl
	ampersand    --fspec latex \
               --predLogic \
               --verbose \
               --interfaces \
               --outputDir=. \
               --language=UK \
               ./qualitymedia.adl

qualitymedia-test.pdf: qualitymedia-test.adl
	$(AMPERSAND)  --fspec=Latex \
                --predLogic \
                --proto=. \
                --verbose \
                --diagnosis \
                --outputDir=. \
                --interfaces \
                --language=EN \
                ./qualitymedia-test.adl
