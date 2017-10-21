REPORT=report

all: $(REPORT).pdf mostlyclean

$(REPORT).pdf: $(REPORT).tex *.txt *.png ../app/* ../db/* ../src/* ../views/* ../web/* ../composer.json
	xelatex -shell-escape $(REPORT).tex
	xelatex -shell-escape $(REPORT).tex

mostlyclean:
	rm -rf _minted-* *.log *.aux *.out *.lof *.lol *.toc

clean:
	rm -rf _minted-* $(REPORT).pdf *.log *.aux *.out *.lof *.lol *.toc

rebuild: clean all
